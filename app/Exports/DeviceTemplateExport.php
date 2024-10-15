<?php

namespace App\Exports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeviceTemplateExport implements withHeadings, FromArray
{   /**
 * Başlıkları döndür.
 */
    public function headings(): array
    {
        return [
            'Type',
            'Brand',
            'Model',
            'Port Number',
            'Building',
            'Unit',
            'Serial Number',
            'Registry Number',
            'Device Name',
            'IP Address',
            'Description',
            'Block',
            'Floor',
            'Room Number',
        ];
    }

    /**
     * Örnek veri (şablon verileri) döndür.
     */
    public function array(): array
    {
        return [
            [
                'Switch',                // Type
                'Cisco',              // Brand
                '9105',              // Model
                48,                     // Port Number (Örnek: 24)
                'Rektörlük',           // Building
                'Bilgi İşlem',               // Unit
                'SN123456',             // Serial Number
                'REG123',               // Registry Number
                'REK',     // Device Name
                '192.168.1.1',          // IP Address
                'aciklama',      // Description
                'A',              // Block
                '1',                    // Floor
                '101',                  // Room Number
            ],
            [
                'Access_point',                // Type
                'Cisco',              // Brand
                '9105',              // Model
                '',                     // Port Number (Örnek: 24)
                'Rektörlük',           // Building
                'Bilgi İşlem',               // Unit
                'SN1234567',             // Serial Number
                'REG1234',               // Registry Number
                'Device Adı',     // Device Name
                '192.168.1.2',          // IP Address
                'Açıklama buraya',      // Description
                'A',              // Block
                '1',                    // Floor
                '101',                  // Room Number
            ],
        ];
    }
}
