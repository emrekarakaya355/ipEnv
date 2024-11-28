<?php

namespace App\Exports;

use App\Models\DeviceType;

class DeviceTypeExport extends BaseExport
{


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
            'Oluşturma Tarihi' => $deviceType->created_at->locale('tr')->translatedFormat('d-M-Y'),

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
