<?php

namespace App\Exports;

use App\Models\Device;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeviceTemplateExport implements withHeadings, FromArray,ShouldAutoSize
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
            'Mac Address',
            'Device Name',
            'IP Address',
            'Description',
            'Block',
            'Floor',
            'Room Number',
            'Status',
            'Parent Ip Address',
            'Parent Port Number',
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
                '',               // Registry Number
                'aa:aa:aa:aa:bb:cc',               // mac address
                'Cihaz Adı',     // Device Name
                '192.168.1.1',          // IP Address
                'aciklama',      // Description
                'A',              // Block
                '1',                    // Floor
                '101',                  // Room Number
                'Pasif,Garanti'
            ],
            [
                'Access_point',                // Type
                'Cisco',              // Brand
                '9105',              // Model
                '',                     // Port Number (Örnek: 24)
                'Rektörlük',           // Building
                'Bilgi İşlem',               // Unit
                'SN1234567',             // Serial Number
                '',               // Registry Number
                'aa:aa:aa:aa:bb:dd',               // Registry Number
                'Cihaz Adı',     // Device Name
                '192.168.1.2',          // IP Address
                'Açıklama buraya',      // Description
                'A',              // Block
                '1',                    // Floor
                '101',                  // Room Number
                'Aktif,Hurda',
                '10.10.10.10',                  // Room Number
                '10',                  // Room Number
            ],
        ];
    }
}
