<?php

namespace App\Exports;

use App\Models\DeviceType;

class DeviceTypeExport extends BaseExport
{
    public function query()
    {
        $query = DeviceType::query();

        // Apply filter criteria if available
        if (!empty($this->filterCriteria)) {
            foreach ($this->filterCriteria as $field => $value) {
                if (!empty($value)) {
                    $query->where($field, $value);
                }
            }
        }

        return $query;
    }

    /**
     * Mapping the data for custom output.
     *
     * @param DeviceType $deviceType
     * @return array
     */
    public function map($deviceType): array
    {
        // Format the date and fetch related 'created by' user
        return [
            'Type' => $deviceType->type,
            'Marka' => $deviceType->brand,
            'Model' => $deviceType->model,
            'Port Sayısı' => $deviceType->port_number,
            'Oluşturma Tarihi' => $deviceType->created_at->format('d-M-Y'), // Only day and year

        ];
    }
    /**
     * Headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return ['Type', 'Marka', 'Model', 'Port Sayısı', 'Oluşturma Tarihi'];
    }
}
