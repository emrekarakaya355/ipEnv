<?php

namespace App\Exports;

use App\Models\Location;

class LocationExport extends BaseExport
{
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
            'Created Date' => $location->created_at->locale('tr')->translatedFormat('d-M-Y'),
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
