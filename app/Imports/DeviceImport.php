<?php

namespace App\Imports;


use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\DeviceType;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class DeviceImport extends  BaseImport
{
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
                'required',
                'string',
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
        ];
    }

    protected function processRow(array $row)
    {

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
                'type' => $row['type'],
                'serial_number' => $row['serial_number'],
                'registry_number' => $row['registry_number'],
                'device_name' => $row['device_name'],
                'status' => 'Depo',
            ]);

            DeviceInfo::create([
                'device_id' => $device->id,
                'location_id' => $location->id,
                'ip_address' => $row['ip_address'],
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
}
