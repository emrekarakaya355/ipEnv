<?php

namespace App\Imports;


use App\Models\Location;

class LocationImport extends BaseImport
{
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
            ],

        ];
    }
    protected function processRow(array $row)
    {
        try {
            // Satırı model olarak kaydedelim
            Location::create([
                'building' => $row['building'],
                'unit' => $row['unit'],
            ]);
        } catch (\Exception $e) {
            if ($this->isUniqueConstraintViolation($e)) {
                // Eğer satır zaten varsa hata olarak işaretleyelim
                $this->fail($row, ['Kayıt zaten var']);
            } else {
                // Diğer hataları tekrar fırlatalım
                $this->fail($row, (array)$e->getMessage());
            }
        }
    }
}
