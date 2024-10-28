<?php

namespace App\Exports;

use App\Models\DeviceType;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeviceTypeTemplateExport implements WithHeadings,FromArray
{
    /**
     * Başlıkları döndür.
     */
    public function headings(): array
    {
        return [
            'Type',
            'Brand',
            'Model',
            'Port_Number',
        ];
    }

    /**
     * Örnek veri (şablon verileri) döndür.
     */
    public function array(): array
    {
        return [
            ['switch', 'Cisco','2960S POE','48'],
            ['access_point', 'Cisco','9105',''],
        ];
    }
}
