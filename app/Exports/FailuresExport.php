<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FailuresExport extends BaseExport implements FromArray, WithHeadings
{
    protected $failures;

    /**
     * FailuresExport constructor.
     * @param array $failures - Import sırasında oluşan hatalar
     * @param array $headings - Excel başlıkları (isteğe bağlı)
     */
    public function __construct(array $failures, $headings = [])
    {
        parent::__construct(); // BaseExport constructor'ını çağır


        $this->failures = $failures;
        $this->headings = $headings ?: ['Row', 'Attribute', 'Errors', 'Values']; // Varsayılan başlıklar

    }

    /**
     * Hatalı satırları döndürür.
     */
    public function array(): array
    {


        $data = [];
        foreach ($this->failures as $failure) {
            $data[] = [
                'row' => $failure->row(),
                'attribute' => implode(', ', (array)$failure->attribute()), // Hatalı alanlar
                'errors' => implode(', ', $failure->errors()), // Hata mesajları
                'values' => implode(', ', $failure->values()), // Hatalı veriler
            ];
        }


        return $data;
    }

    /**
     * Excel için başlıkları döndürür.
     */
    public function headings(): array
    {
        return $this->headings;
    }

    public function query()
    {
        return collect($this->failures);

    }

    public function map($row): array
    {
        return [

            $row['row'],
            $row['values'],
            $row['attribute'],
            $row['errors'],
        ];
    }
}
