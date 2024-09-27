<?php

namespace App\Exports;

use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LocationTemplateExport implements WithHeadings, FromArray
{
    /**
     * Başlıkları döndür.
     */
    public function headings(): array
    {
        return [
            'Building',
            'Unit',
        ];
    }

    /**
     * Örnek veri (şablon verileri) döndür.
     */
    public function array(): array
    {
        return [
            ['Rektörlük', 'Bilgi İşlem Dairesi'],
        ];
    }
}
