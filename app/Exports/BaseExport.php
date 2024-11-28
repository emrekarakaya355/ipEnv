<?php

namespace App\Exports;

use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

abstract class BaseExport implements FromQuery, FromArray, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected $model;
    protected $filterCriteria;
    protected $headings;
    protected $data;

    /**
     * BaseExport constructor.
     * @param string|null $model - Dinamik model sınıfı (isteğe bağlı)
     * @param array $filterCriteria - Filtreleme kriterleri (isteğe bağlı)
     * @param array $data - Dizi olarak veriler (isteğe bağlı)
     * @param array $headings - Excel başlıkları (isteğe bağlı)
     */
    public function __construct(string $model = null, array $filterCriteria = [], array $data = [], array $headings = [])
    {
        $this->model = $model;
        $this->filterCriteria = $filterCriteria;
        $this->data = $data;
        // Automatically set headings if a model is provided
        if ($this->model) {
            $this->headings = $this->getHeadingsFromModel();
        } else {
            $this->headings = []; // Default to empty if no model is provided
        }

    }

    // New method to fetch headings dynamically
    protected function getHeadingsFromModel(): array
    {
        if (class_exists($this->model)) {
            return Schema::getColumnListing((new $this->model())->getTable());
        }

        return []; // Return empty if model doesn't exist
    }

    /**
     * Abstract method to be implemented by child classes
     */
    public function query()
    {
        if (!class_exists($this->model)) {
            throw new \Exception("Model sınıfı bulunamadı: " . $this->model);
        }

        // Dinamik olarak modeli oluştur
        $modelInstance = new $this->model();
        $query = $modelInstance->query();

        // Apply filter criteria if available
        if (!empty($this->filterCriteria)) {
            foreach ($this->filterCriteria as $field => $value) {
                if (!empty($value)) {
                    $query->where($field, 'LIKE', '%' . $value . '%');
                }
            }
        }
        return $query;
    }

    /**
     * Returns data as an array if provided.
     */
    public function array(): array
    {
        if (!empty($this->data)) {
            return $this->data;
        }
        return [];
    }

    /**
     * Returns headings for Excel.
     */
    public function headings(): array
    {
        return $this->headings ?: ['Alan 1', 'Alan 2','Alan 3','Alan 4' ]; // Default headings
    }

}
