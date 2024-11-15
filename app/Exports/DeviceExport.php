<?php
namespace App\Exports;

use App\Models\Device;
use App\Services\DeviceService;

class DeviceExport extends BaseExport
{
    public function query()
    {
        $modelInstance = new $this->model();
        $query = $modelInstance->newQuery();

        // request ve gerekli parametreleri alarak search metodunu çağırıyoruz
        (new DeviceService())->filter(request(), $query);
        return $query->sorted(request('sort', 'created_at'), request('direction', 'desc'));
    }


    /**
     * Mapping the data for custom output.
     *
     * @param Device $device
     * @return array
     */
    public function map($device): array
    {

        $data =  [
            'device_name' => $device->device_name ?? '',
            'mac_address' => $device->mac_address ?? '',
            'serial_number' => $device->serial_number ?? '',
            'registry_number' => $device->registry_number ?? '',
            'type' => $device->type ?? '',
            'brand' => optional($device->deviceType)->brand ?? '',
            'model' => optional($device->deviceType)->model ?? '',
            'port_number' => optional($device->deviceType)->port_number ?? '',
            'ip_address' => optional($device->latestDeviceInfo)->ip_address ?? '',
            'building' => optional($device->latestDeviceLocation)->building ?? '',
            'unit' => optional($device->latestDeviceLocation)->unit ?? '',
            'status' => $device->status->value ?? '',
            'created_at' => $device->created_at->locale('tr')->translatedFormat('d-M-Y'),
            'created_by' => optional($device->createdBy)->name ?? '',
        ];
        // Seçilen sütunları al
        $selectedColumns = $this->getColumns();

        // Sadece seçilen sütunları döndür
        return array_intersect_key($data, array_flip($selectedColumns));
    }

    /**
     * Headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        $allHeadings = $this->getAllHeading();
        // Seçilen sütunları al
        $selectedColumns = $this->getColumns();
        // Sadece seçilen sütunların başlıklarını döndür
        return array_intersect_key($allHeadings, array_flip($selectedColumns));
    }

    private function getColumns(): array
    {
        $selectedColumns = json_decode(request()->get('selected_columns'), true);
        //eğer selected boş ise her şeyi seç
        if(!$selectedColumns){
            return $this->getAllHeading();
        }
        $alwaysVisibleColumns = ['created_at', 'created_by'];
        return array_merge($selectedColumns,$alwaysVisibleColumns);
    }

    private function getAllHeading()
    {
        // Tüm olası başlıklar
        return  [
            'device_name' => 'Cihaz Adı',
            'mac_address' => 'Mac Address',
            'serial_number' => 'Seri Numarası',
            'registry_number' => 'Sicil Numarası',
            'type' => 'Cihaz Tipi',
            'brand' => 'Marka',
            'model' => 'Model',
            'port_number' => 'Port Numarası',
            'ip_address' => 'İp Adresi',
            'building' => 'Bina',
            'unit' => 'Birim',
            'status' => 'Durumu',
            'created_at' => 'Oluşturma Tarihi',
            'created_by' => 'Oluşturan Kullanıcı',
        ];

    }
}

