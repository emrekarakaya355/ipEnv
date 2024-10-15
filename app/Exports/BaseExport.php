<?php

namespace App\Exports;

use App\Exceptions\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

abstract class BaseExport implements FromQuery, FromArray, WithMapping, WithHeadings,  WithColumnWidths
{
    use Exportable;

    protected $model;
    protected $filterCriteria;
    protected $headings;
    protected $data;
    protected $columnWidths; // Property to hold dynamic widths

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

        // Set default column widths based on headings
        $this->columnWidths = $this->calculateColumnWidths();
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
        return $this->headings ?: ['ID', 'Field 1', 'Field 2']; // Default headings
    }

    // Calculate dynamic column widths based on headings
    protected function calculateColumnWidths(): array
    {
        $widths = [];
        foreach ($this->headings as $index => $heading) {
            // Set width based on heading length, plus some buffer, and possibly max data length
            $width = strlen($heading) + 5; // Base width based on heading length
            if (!empty($this->data)) {
                // If there is data, check for the longest entry in that column
                foreach ($this->data as $row) {
                    if (isset($row[$index])) {
                        $dataLength = strlen($row[$index]);
                        if ($dataLength > $width) {
                            $width = $dataLength + 5; // Adjust width if data is longer
                        }
                    }
                }
            }
            $widths[chr(65 + $index)] = $width; // Convert index to corresponding column letter
        }
        return $widths;
    }

    // Implement the column widths method
    public function columnWidths(): array
    {
        return $this->columnWidths; // Return dynamic column widths
    }
}
