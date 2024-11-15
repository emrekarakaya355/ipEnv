<?php

namespace App\Imports;


use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\DeviceType;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class DeviceImport extends  BaseImport
{
    private bool $hasParent =false;

    /* işe yaramıyor
    // Başlıklar için bir eşleme (mapping) dizisi
    protected $headerMapping = [
        'type' => ['type', 'cihaz tipi', 'device type'],
        'model' => ['model', 'model adı'],
        'brand' => ['brand', 'marka'],
        'port_number' => ['port_number', 'port sayısı', 'port numarası'],
        'building' => ['building', 'bina', 'yerleşke','fakülte'],
        'unit' => ['unit', 'birim'],
        'serial_number' => ['serial_number', 'seri numarası'],
        'registry_number' => ['registry_number', 'sicil numarası','sicil no'],
        'mac_address' => ['mac_address', 'mac', 'mac adresi','mac no'],
        'device_name' => ['device_name', 'cihaz adı', 'ad','isim'],
        'ip_address' => ['ip_address', 'ip adresi','ip'],
        'description' => ['description', 'açıklama'],
        'block' => ['block', 'blok'],
        'floor' => ['floor', 'kat'],
        'room_number' => ['room_number', 'oda numarası','oda no'],
        'parent_ip_address' => ['parent_ip_address', 'remote ip adresi','remote ip','remote adres'],
        'parent_port_number' => ['parent_port_number', 'remote port numarası','remote port no'],
    ];

    protected function mapHeaders(array $row): array
    {
        $mappedRow = [];

        foreach ($row as $header => $value) {
            $normalizedHeader = strtolower(trim($header));

            foreach ($this->headerMapping as $standardHeader => $aliases) {
                if (in_array($normalizedHeader, $aliases)) {
                    $mappedRow[$standardHeader] = $value;
                    break;
                }
            }
        }

        return $mappedRow;
    }
*/
    public function rules(): array
    {
        return [
            /*
             * Cihaz Tipi Bilgileri
             * */
            'type' => [
                'required',
                function ($attribute, $value, $fail) {
                    $lowerValue = strtolower($value);
                    if (!in_array($lowerValue, ['switch', 'access_point'])) {
                        $fail($attribute . ' alanı switch ya da access_point olmalıdır.');
                    }
                },
            ],
            'model' => [
                'required',
            ],
            'brand' => [
                'required',
            ],
            'port_number' => [
                'required_if:type,switch', // 'port_number' is required if 'type' is 'switch'
                'nullable', // Allows it to be nullable otherwise
                'integer',  // Ensures 'port_number' is an integer when provided
            ],
            /*
             * Location Bilgileri
             * */
            'building' => [
                'required',
                'string',
            ],
            'unit' => [
                'required',
                'string',
            ],
            /*
             * device bilgileri
             * */
            'serial_number' => [
                'required',
                'string',
            ],
            'registry_number' => [
                'nullable',
                'string',
            ],
            'mac_address' => [
                'required',
                'string',
                'regex:/^([0-9A-Fa-f]{2}([-:])?){5}[0-9A-Fa-f]{2}$/', // Ensures MAC address format
                'unique:devices,mac_address', // Optional: check for uniqueness if needed
            ],
            'device_name' => [
                'nullable',
                'string',
            ],

            /*
             * device_Info bilgileri
             * **/
            'ip_address' => [
                'nullable',
                'ipv4',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'block' => [
                'nullable',
            ],
            'floor' => [
                'nullable',
            ],
            'room_number' => [
                'nullable',
            ],

            /*  Parent bilgileri*/
            'parent_ip_address' => [
                'nullable',
                'ipv4',
            ],
            'parent_port_number' => [
                'nullable',
                'integer',
            ],
        ];
    }

    protected function processRow(array $row)
    {

        //$row = $this->mapHeaders($row); // Başlıkları eşle

        //eğer herhangi bir satırda parent device id var ise hasParent true olsun
            if($row['parent_ip_address']){
                $this->hasParent = true;
            }

            $deviceType = DeviceType::where('type', $row['type'])
                ->where('brand', $row['brand'])
                ->where('model', $row['model'])
                ->where('port_number', $row['port_number'])
                ->first();

            if(!$deviceType){
                $this->fail($row, (array)'Cihaz Tipi Bulunamadı!.');
                return;
            }

            $location = Location::where('building', $row['building'])
                ->where('unit', $row['unit'])
                ->first();

            if(!$location){
                $this->fail($row, (array)'Yer Bilgisi Bulunamadı!.');
                return ;
            }
        try {
            DB::beginTransaction(); // Transaction başlat

            // Satırı model olarak kaydedelim
            $device = Device::create([
                'device_type_id' => $deviceType->id,
                'type' => strtolower(trim($row['type'])),
                'serial_number' => strtolower(trim($row['serial_number'])),
                'registry_number' => strtolower(trim($row['registry_number'])),
                'mac_address' => strtolower(trim($row['mac_address'])),
                'device_name' => $row['device_name'],
                'status' => 'Depo',
            ]);

            DeviceInfo::create([
                'device_id' => $device->id,
                'location_id' => $location->id,
                'ip_address' => trim($row['ip_address']),
                'description' => 'Toplu Kayıt',
                'block' => $row['block'],
                'floor' => $row['floor'],
                'room_number' => $row['room_number'],
            ]);

            DB::commit(); // Eğer her şey başarılıysa commit yap
        } catch (\Exception $e) {
            // Diğer hataları tekrar fırlatalım
            DB::rollBack(); // Hata olursa rollback yap
            $this->fail($row, (array)$e->getMessage());
        }
    }


    public function hasParent(): bool
    {
        return $this->hasParent;
    }
}
