<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;

class FilteredExport extends BaseExport implements FromQuery, WithHeadings
{
    protected $model;
    protected $filterCriteria;
    protected $headings;


    public function __construct(string $model = null, $filterCriteria = [], $headings = [])
    {
        $this->model = $model; // Dinamik model
        $this->filterCriteria = $filterCriteria; // Filtre kriterleri
        $this->headings = $headings; // Dinamik başlıklar
    }

    public function query()
    {
        // Dinamik model ile sorgu oluşturuluyor
        return $this->model::query()
            ->where($this->filterCriteria); // Filtre kriterleri uygulanıyor
    }

    public function headings(): array
    {
        // Dinamik başlıklar döndürülüyor
        return $this->headings;
    }
}

