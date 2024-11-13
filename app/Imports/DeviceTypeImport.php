<?php

namespace App\Imports;

use App\Models\DeviceType;

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

    private function isUnique($type, $brand, $model, $port_number)
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

            if($this->isUnique($row['type'], $row['brand'], $row['model'], $row['port_number'])){
                $this->fail($row, (array)'Aynı Kayıt Var.');
                return;
            }

            // Satırı model olarak kaydedelim
            DeviceType::create([
                'type' =>ucfirst(strtolower( $row['type'])),
                'brand' => ucfirst(strtolower( $row['brand'])),
                'model' =>ucfirst(strtolower( $row['model'])),
                'port_number' =>$row['port_number'],
            ]);

        } catch (\Exception $e) {
            $this->fail($row, (array)$e->getMessage());

        }
    }

}
