<?php

namespace App\Http\Requests;

use App\DeviceStatus;
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
            'parent_device_id' =>'nullable|exists:devices,id',


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
}
