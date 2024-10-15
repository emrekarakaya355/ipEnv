<?php
namespace App\Exports;

use App\Models\Device;
use App\Services\DeviceService;

class DeviceExport extends BaseExport
{

/*
    public function query()
    {
        // Mevcut search() fonksiyonunu kullanarak sorgu oluştur
        $modelInstance = new $this->model();
        $query = $modelInstance->newQuery();

        // request ve gerekli parametreleri alarak search metodunu çağırıyoruz
        (new DeviceService())->filter(request(), $query);
        // Dışa aktarım için 'selected_columns' parametresini al
        $selectedColumns = request()->get('selected_columns');

        if ($selectedColumns) {
            // Seçilen sütunları diziye dönüştür
            $columnsArray = json_decode($selectedColumns, true);

            // Eğer sütun dizisi geçerliyse, sorguya sadece bu sütunları ekle
            if (is_array($columnsArray)) {

                $query->select($columnsArray);

            }
        }
        dd($query->toSql());
        // Verilerin dışa aktarımı için pagination yerine düz get() kullanıyoruz
        return $query;  // Dışa aktarım için verileri al
    }*/
    public function query()
    {
        // Mevcut search() fonksiyonunu kullanarak sorgu oluştur
        $modelInstance = new $this->model();
        $query = $modelInstance->newQuery();

        // request ve gerekli parametreleri alarak search metodunu çağırıyoruz
        (new DeviceService())->filter(request(), $query);
        return $query;  // Dışa aktarım için verileri al
    }


    /**
     * Mapping the data for custom output.
     *
     * @param Device $device
     * @return array
     */
    public function map($device): array
    {

        // Seçilen sütunları al
        $selectedColumns = json_decode(request()->get('selected_columns'), true);
        // Varsayılan değerleri belirle
        // Her zaman görünmesini istediğiniz sütunları ekle
        $alwaysVisibleColumns = ['created Date', 'created By'];

        // Seçilen sütunlara bu sütunları ekle
        $selectedColumns = array_merge($selectedColumns, $alwaysVisibleColumns);

        $data =  [
            'building' => optional($device->latestDeviceLocation)->building ?? 'N/A',
            'unit' => optional($device->latestDeviceLocation)->unit ?? 'N/A',
            'type' => $device->type ?? 'N/A',
            'brand' => optional($device->deviceType)->brand ?? 'N/A',
            'model' => optional($device->deviceType)->model ?? 'N/A',
            'port Number' => $device->port_number ?? 'N/A',
            'serial Number' => $device->serial_number ?? 'N/A',
            'registry Number' => $device->registry_number ?? 'N/A',
            'ip_address' => optional($device->latestDeviceInfo)->ip_address ?? 'N/A',
            'device Name' => $device->device_name ?? 'N/A',
            'status' => $device->status->value ?? 'N/A',
            'created Date' => $device->created_at->format('d-M-Y'),
            'created By' => optional($device->createdBy)->name ?? 'N/A',
        ];

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
        // Seçilen sütunları al
        $selectedColumns = json_decode(request()->get('selected_columns'), true);

        // Her zaman görünmesini istediğiniz sütunları ekle
        $alwaysVisibleColumns = ['created Date', 'created By'];

        // Seçilen sütunlara bu sütunları ekle
        $selectedColumns = array_merge($selectedColumns, $alwaysVisibleColumns);

        // Tüm olası başlıklar
        $allHeadings = [
            'building' => 'Bina',
            'unit' => 'Birim',
            'type' => 'Cihaz Tipi',
            'brand' => 'Marka',
            'model' => 'Model',
            'port Number' => 'Port Numarası',
            'serial Number' => 'Seri Numarası',
            'registry Number' => 'Sicil Number',
            'ip_address' => 'İp Adresi',
            'device Name' => 'Cihaz Name',
            'status' => 'Durumu',
            'created Date' => 'Oluşturma Tarihi',
            'created By' => 'Oluşturan Kişi',
        ];

        // Sadece seçilen sütunların başlıklarını döndür
        return array_intersect_key($allHeadings, array_flip($selectedColumns));
    }
}

