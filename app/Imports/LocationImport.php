<?php

namespace App\Imports;


use App\Models\Location;

class LocationImport extends BaseImport
{
    /**
     * @param array $row
     * @return mixed
     */
    public function model(array $row)
    {

            return $this->createLocation($row);

    }
    public function rules(): array
    {
        return [
            'building' => [
                'required',
                'string',
            ],
            'unit' => [
                'required',
                'string',
                'unique:locations,unit,NULL,id,building,' .  request()->input('building'), // unit ve building kombinasyonu için benzersiz kural
            ],

        ];
    }
    public function customValidationMessages()
    {
        return [
            'unit.unique' => 'Kayıt Zaten Var',
        ];
    }
    protected function createLocation(array $row)
    {
        try {
            return Location::Create(
                [
                    'building' => $row['building'],
                    'unit' => $row['unit']
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
