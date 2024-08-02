<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\DeviceType;
use App\Models\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceService
{
    public function createDeviceWithInfo($deviceValidated)
    {
        return DB::transaction(function () use ($deviceValidated) {

            $device = $this->createDevice($deviceValidated);
            $this->createDeviceInfo($deviceValidated, $device);
        });

    }

    public function updateDeviceWithInfo($deviceValidated,$device)
    {

        return DB::transaction(function () use ($deviceValidated, $device) {

            //değişiklik varmı diye dolduruyoruz.
            $this->fillDevice($deviceValidated,$device);
            $deviceInfo = $this->fillDeviceInfo($deviceValidated,$device);

            if (!$device->isDirty() and !$deviceInfo->isDirty()) {
                if ($deviceValidated['description'] !== null){ // eğer sadece açıklama değişti ise update ediliyor yeni info eklenmiyor.
                    $deviceInfo->update(['description' => $deviceValidated['description']]);
                    return response()->json([
                        'success' => true,
                    ], 200); // 200 OK
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Herhangi bir değişiklik yapılmadı.'
                ], 500); // 200 OK
            }

            if ($device->isDirty()){

                $device->update();
            }
            if ($deviceInfo->isDirty()) {
                //eğer değişiklik var ise en son kayıt için update reason güncelleniyor.
                $deviceInfo = $deviceInfo->fresh();
                $deviceInfo->update(['update_reason' => $deviceValidated['update_reason']]);

                //yeni info ekleniyor.
                 $this->createDeviceInfo($deviceValidated,$device);

            }
            return response()->json([
                'success' => true,
            ], 200); // 200 OK
        });
    }
    public function search(Request $request,$type): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $type::query();
        if ($search) {
            $query->where('device_name', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%')
                ->orWhere('serial_number', 'like', '%' . $search . '%')

                ->orWhereHas('latestDeviceInfo.location', function ($q) use ($search) {
                    $q->where('building', 'like', '%' . $search . '%')
                        ->orWhere('unit', 'like', '%' . $search . '%')
                        ->orWhere('ip_address', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                })
                ->orWhereHas('deviceType', function ($q) use ($search) {
                    $q->where('model', 'like', '%' . $search . '%')
                        ->orWhere('brand', 'like', '%' . $search . '%');
                });
        }
        return $query->with('latestDeviceInfo')->sorted()->paginate(10);
    }

    private function createDeviceInfo( $deviceValidated, $device) : DeviceInfo
    {

        $locationId = Location::getLocationIdFromBuildingAndUnit($deviceValidated['building'], $deviceValidated['unit']);

        return DeviceInfo::create([
             'device_id' => $device->id,
             'ip_address' => $deviceValidated['ip_address'],
             'location_id' =>$locationId,
             'block' => $deviceValidated['block'],
             'floor' => $deviceValidated['floor'],
             'room_number' => $deviceValidated['room_number'],
             'description' => $deviceValidated['description'],
         ]);

    }

    private function createDevice($deviceValidated){
        $deviceType = DeviceType::getDeviceType($deviceValidated['type'],$deviceValidated['model'], $deviceValidated['brand']);

        if($deviceType === null){
            return response()->json('Cihaz Tipi Bulunamadı',false);
        }

        $deviceData = [
            'type' => $deviceType->type,
            'device_type_id' => $deviceType->id,
            'device_name' => $deviceValidated['device_name'],
            'serial_number' => $deviceValidated['serial_number'],
            'registry_number' => $deviceValidated['registry_number'],
            'parent_device_id' => $deviceValidated['parent_device_id'],
            'status' => $deviceValidated['ip_address'] === null ? "Depo" : "Çalışıyor",
        ];

        $device = Device::create($deviceData);

        return $device;
    }


    private function fillDevice($deviceValidated,$device){
        $deviceType = DeviceType::getDeviceType($deviceValidated['type'],$deviceValidated['model'], $deviceValidated['brand']);

        // Device verilerinde değişiklik kontrolü
        $deviceData = ([
            'type' => $deviceType->type,
            'device_type_id' => $deviceType->id,
            'device_name' => $deviceValidated['device_name'],
            'serial_number' => $deviceValidated['serial_number'],
            'registry_number' => $deviceValidated['registry_number'],
            //'parent_device_id' => $deviceValidated['parent_device_id'] === null ? "" : $deviceValidated['parent_device_id'],
            'status' => $deviceValidated['status'],
        ]);

        $device->fill($deviceData);
    }

    private function fillDeviceInfo($deviceValidated,$device){

        $locationId = Location::getLocationIdFromBuildingAndUnit($deviceValidated['building'], $deviceValidated['unit']);
        $deviceInfo = $device->latestDeviceInfo;

        // DeviceInfo verilerinde değişiklik kontrolü
        $deviceInfoData = [
            'device_id' => $device->id,
            'ip_address' => $deviceValidated['ip_address'],
            'location_id' =>  $locationId,
            'block' => $deviceValidated['block'],
            'floor' => $deviceValidated['floor'],
            'room_number' => $deviceValidated['room_number'],
        ];
        $deviceInfo->fill($deviceInfoData);

        return $deviceInfo;
    }


}
