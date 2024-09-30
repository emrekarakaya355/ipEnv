<?php
namespace App\Exports;

use App\Models\Device;

class DeviceExport extends BaseExport
{
    /**
     * Override the query method for the Device model.
     */
    public function query()
    {
        $query = Device::query();

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
     * @param Device $device
     * @return array
     */
    public function map($device): array
    {
        // Format the date and fetch related 'created by' user
        return [

            'Location' => optional($device->latestDeviceLocation)->building ?? 'N/A',
            'Unit' => optional($device->latestDeviceLocation)->unit ?? 'N/A',
            'Type' => $device->type ?? 'N/A',
            'Model' => optional($device->deviceType)->model ?? 'N/A',
            'Brand' => optional($device->deviceType)->brand ?? 'N/A',
            'Port Number' => $device->port_number ?? 'N/A',
            'Serial Number' => $device->serial_number ?? 'N/A',
            'Registry Number' => $device->registry_number ?? 'N/A',
            'Ip_address' => optional($device->latestDeviceInfo)->ip_address ?? 'N/A',
            'Device Name' => $device->device_name ?? 'N/A',
            'Status' => $device->status->value ?? 'N/A',
            'Created Date' => $device->created_at->format('d-M-Y'),
            'Created By' => optional($device->createdBy)->name ?? 'N/A',

        ];
    }

    /**
     * Headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [

            'Building',
            'Unit',
            'Type',
            'Model',
            'Brand',
            'Port Number',
            'Serial Number',
            'Registry Number',
            'Device Name',
            'Ip_address',
            'Status',
            'Created Date',
            'Created By',
        ];
    }
}

