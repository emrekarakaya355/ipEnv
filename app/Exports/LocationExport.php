<?php

namespace App\Exports;

use App\Models\Location;

class LocationExport extends BaseExport
{
    /**
     * Override the query method for the Location model.
     */
    public function query()
    {
        $query = Location::query();

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
     * @param Location $location
     * @return array
     */
    public function map($location): array
    {
        // Format the date and fetch related 'created by' user
        return [
            'Building' => $location->building,
            'Unit' => $location->unit,
            'Created Date' => $location->created_at->format('d-M-Y'), // Only day and year
            'Created By' => optional($location->createdBy)->name, // Assuming Location has a relationship with User
        ];
    }

    /**
     * Headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [ 'Building', 'Unit', 'Created Date', 'Created By'];
    }
}
