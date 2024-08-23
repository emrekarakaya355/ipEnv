<?php

namespace App\Services;

use App\Exceptions\ModelNotFoundException;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Models\Device;
use App\Models\DeviceInfo;
use App\Models\DeviceType;
use App\Models\Location;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeviceService
{
    public function createDeviceWithInfo($deviceValidated)
    {
        $device = null;

        try {

            //hem device hem info beraber oluşturulması için transaction kullanıyoruz.
            DB::transaction(function () use ($deviceValidated, &$device) {
                $device = $this->createDevice($deviceValidated);
                //Gereksiz ama ne olur ne olmaz diye.
                if ($device instanceof Device) {
                    $this->createDeviceInfo($deviceValidated, $device);
                }

            });
            $routname = route('devices.show', $device->id);
            return new SuccessResponse('Cihaz Başarı İle Oluşturuldu.',['data' => $device->id],$routname);
        } catch (\Exception $exception) {
            return new ErrorResponse($exception,'Cihaz Oluşturulamadı. Lütfen tekrar deneyiniz.');
        }
    }
    public function updateDeviceWithInfo($deviceValidated, $device)
    {

        return DB::transaction(function () use ($deviceValidated, $device) {
            //değişiklik varmı diye dolduruyoruz.
            $this->fillDevice($deviceValidated,$device);
            $deviceInfo = $this->fillDeviceInfo($deviceValidated,$device);
            if (!$device->isDirty() and !$deviceInfo->isDirty()) {
                if ($deviceValidated['description'] !== null){ // eğer sadece açıklama değişti ise update ediliyor yeni info eklenmiyor.
                    $deviceInfo->update(['description' => $deviceValidated['description']]);
                    return new SuccessResponse('Açıklama Update Edildi.',['data' => $deviceInfo->id]);
                }
                return new ErrorResponse(null,'Herhangi bir değişiklik yapılmadı.');
            }
            if ($deviceInfo->isDirty()) {
                //eğer ip adresi değiştiyse

                if($deviceInfo->isDirty('ip_address')){
                    foreach($device->connectedDevices->all() as $childDevice){
                        $childDevice->parent_device_id = null;
                        $childDevice->save();
                    }
                }
                //eğer değişiklik var ise en son kayıt için update reason güncelleniyor.
                $deviceInfo = $deviceInfo->fresh();
                $deviceInfo->update(['update_reason' => $deviceValidated['update_reason']]);
                //yeni info ekleniyor.
                 $this->createDeviceInfo($deviceValidated,$device);
            }
            if ($device->isDirty()){
                $device->save();
            }
            return new SuccessResponse('Cihaz Başarı İle Update Edildi.',['data' => $device->id]);
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

    private function createDeviceInfo( $deviceValidated, $device) : void
    {
        $locationId = Location::getLocationIdFromBuildingAndUnit($deviceValidated['building'], $deviceValidated['unit']);
        DeviceInfo::create([
             'device_id' => $device->id,
             'ip_address' => $deviceValidated['ip_address'],
             'location_id' =>$locationId,
             'block' => $deviceValidated['block'],
             'floor' => $deviceValidated['floor'],
             'room_number' => $deviceValidated['room_number'],
             'description' => $deviceValidated['description'],
         ]);
    }
    /**
     * @throws
     */
    private function createDevice($deviceValidated){


        $deviceType = DeviceType::getDeviceType($deviceValidated['type'],$deviceValidated['model'], $deviceValidated['brand']);

        if($deviceType === null){
            throw new ModelNotFoundException('Cihaz Tipi Bulunamadı!');
        }

        $attributes = [
            'type' => $deviceType->type,
            'device_type_id' => $deviceType->id,
            'device_name' => $deviceValidated['device_name'],
            'serial_number' => $deviceValidated['serial_number'],
            'registry_number' => $deviceValidated['registry_number'],
            'parent_device_id' => $deviceValidated['parent_device_id'],
            'parent_device_port' => $deviceValidated['parent_device_port'],
            'status' => $deviceValidated['ip_address'] === null ? "Depo" : "Çalışıyor",
        ];
        return Device::create($attributes);
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
            'parent_device_id' => $deviceValidated['parent_device_id'],
            'parent_device_port' => $deviceValidated['parent_device_port'],
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
            'parent_device_id' => $deviceValidated['parent_device_id'],
        ];

        $deviceInfo->fill($deviceInfoData);

        return $deviceInfo;
    }


}
