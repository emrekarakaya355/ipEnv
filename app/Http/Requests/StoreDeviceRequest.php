<?php

namespace App\Http\Requests;

use App\Enums\DeviceStatus;
use App\Models\Device;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {

        return array_merge([
            'type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'device_name' => 'nullable|string|max:255',
            'serial_number' => 'required|string|max:255',
            'registry_number' => 'nullable|string|max:255',
            'status' => ['nullable', 'string', 'in:' . implode(',', DeviceStatus::toArray())],
            'parent_device_id' =>[
                'nullable',
                'exists:devices,id',function ($attribute, $value, $fail) {
                    $deviceId = $this->route('device')->id ?? null;
                    $parentDevice = Device::find($value);
                    $oldParentDeviceId = $this->route('device')->parent_device_id ?? null;

                    if ((int) $value !== $oldParentDeviceId) {
                        // Döngüsel referans kontrolü yap
                        if ((int) $value == $deviceId ) {
                             $fail('Cihazın Kendini Parent olarak seçemezsiniz.');
                             return false;
                        }
                         if($this->hasCircularReference($deviceId, $parentDevice)){
                             $fail('hata oluştu');
                             return false;
                         }
                    }
                    return false;
                },
            ],
            'parent_device_port' => 'nullable|integer|max:255',


        ], $this->deviceInfoRules());
    }

    /**
     * Get the validation rules for the device info.
     *
     * @return array
     */
    protected function deviceInfoRules(): array
    {
        return (new StoreDeviceInfoRequest())->rules();
    }

    /**
     * @throws \Exception
     */
    protected function hasCircularReference($deviceId, $parentDevice): bool
    {
        if (!$parentDevice) {

            throw new \Exception('Seçilen Parent Bulunamadı.');
        }
        $device = Device::find($deviceId);

        if ($parentDevice->id === $deviceId) {
            throw new \Exception('Cihazın çocuklarından birini parent olarak seçemezsiniz.');
        }

        foreach ($device->connectedDevices as $connectedDevice) {
            $this->hasCircularReference($connectedDevice->id, $parentDevice);
        }

        return false;
    }
}
