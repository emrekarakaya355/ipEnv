<?php

namespace App\Imports;

use App\Exceptions\ConflictException;
use App\Http\Responses\ValidatorResponse;
use App\Models\DeviceType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class DeviceTypeImport extends BaseImport
{


    public function rules(): array
    {
        return [
            'type' => [
                'required',
                function ($attribute, $value, $fail) {
                    $lowerValue = strtolower($value);
                    if (!in_array($lowerValue, ['switch', 'access_point'])) {
                        $fail($attribute . ' alanı switch ya da access_point olmalıdır.');
                    }
                },
            ],
            'brand' => ['required'],
            'model' => ['required'],
            'port_number' => [
                'required_if:type,switch', // 'port_number' is required if 'type' is 'switch'
                'nullable', // Allows it to be nullable otherwise
                'integer',  // Ensures 'port_number' is an integer when provided
            ],
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($validator->getData() as $data) {
                if ($this->isUnique($data['type'], $data['brand'], $data['model'], $data['port_number'])) {
                    $validator->errors()->add('type', 'Bu kayıt zaten var.');
                }
            }
        });
    }
    protected function isUnique($type, $brand, $model, $port_number)
    {
        return DeviceType::where('type', $type)
            ->where('brand', $brand)
            ->where('model', $model)
            ->where('port_number', $port_number)
            ->exists();
    }
    protected function processRow(array $row)
    {
        try {
            // Satırı model olarak kaydedelim
            DeviceType::create([
                'type' => $row['type'],
                'brand' => $row['brand'],
                'model' => $row['model'],
                'port_number' => $row['port_number'],
            ]);
        } catch (\Exception $e) {
            $this->fail($row, (array)$e->getMessage());

        }
    }

}
