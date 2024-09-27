<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ErrorAndFailuresExport extends BaseExport implements FromArray, WithHeadings, WithMapping
{
    protected $failures;
    protected $errors;
    protected $headings;

    /**
     * ErrorAndFailuresExport constructor.
     *
     * @param array $failures
     * @param array $errors
     * @param array $headings
     */
    public function __construct(array $failures, array $errors, array $headings = [])
    {
        parent::__construct(); // BaseExport constructor'ını çağır

        $this->failures = $failures;
        $this->errors = $errors;

        // Varsayılan başlıklar
        $this->headings = $headings ?: ['Row', 'Attribute', 'Errors', 'Values'];
    }

    /**
     * Hataları ve başarısızlıkları dizi olarak döndürür.
     *
     * @return array
     */
    public function array(): array
    {
        $data = [];

        // Başarısızlıkları ekleyin
        foreach ($this->failures as $failure) {
            $data[] = [
                'row' => $failure->row(),
                'attribute' => implode(', ', (array)$failure->attribute()),
                'errors' => implode(', ', $failure->errors()),
                'values' => implode(', ', $failure->values()),
            ];
        }

        // Hataları ekleyin
        foreach ($this->errors as $error) {
            $data[] = [
                'row' => 'N/A', // Hata satırı belirtilmediği için
                'attribute' => 'N/A',
                'errors' => $error->getMessage(), // Hata mesajı
                'values' => 'N/A',
            ];
        }

        return $data;
    }

    /**
     * Excel için başlıkları döndürür.
     *
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * Excel için verileri haritalar.
     *
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row['row'],
            $row['attribute'],
            $row['errors'],
            $row['values'],
        ];
    }

    public function query()
    {
        // TODO: Implement query() method.
    }
}
