<?php

namespace App\Imports;

use App\Exceptions\ConflictException;
use App\Http\Responses\ValidatorResponse;
use App\Models\DeviceType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation;

class DeviceTypeImport extends BaseImport implements WithValidation
{
    /**
    * @param array $row
    */
    public function model(array $row)
    {
        return $this->createDeviceType($row);

    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                function ($attribute, $value, $fail) {
                    $lowerValue = strtolower($value);
                    if (!in_array($lowerValue, ['switch', 'access_point'])) {
                        $fail( $attribute . 'alanı switch ya da access_point olmalıdır.');
                    }
                },
            ],
            'brand' => [
                'required',
            ],
            'model' => [
                'required',
            ],
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
            foreach($validator->getData() as $key=>$data){
                if($this->isUnique($data['type'], $data['brand'], $data['model'], $data['port_number'])){
                    $validator->errors()->add('type', 'Bu Kayıt Zaten Var.dadada');
                    // print() yerine validasyon hatalarını kontrol ediyoruz
                }
            }
        });
    }

    private function isUnique($type,$brand,$model,$port_number){
        return DeviceType::where('type', $type)->where('brand', $brand)->where('model', $model)->where('port_number', $port_number)->exists();
    }
    protected function createDeviceType(array $row)
    {
        try {
            return DeviceType::Create(
                [
                    'type' => $row['type'],
                    'brand' => $row['brand'],
                    'model' => $row['model'],
                    'port_number' => $row['port_number'],
                ]
            );

        } catch (\Exception $e) {
            // Hatanın unique violation olup olmadığını kontrol et
            if ($this->isUniqueConstraintViolation($e)) {
                // Validasyon hatası gibi ele al ve Failure dizisine ekle
                $this->fail($row, ['Kayıt Zaten var']);
            } else {
                // Diğer hataları onError'a yönlendir
                $this->onError($e);
            }
        }
        return null;
    }
}
