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
                if($deviceInfo->isDirty('ip_address')){
                    foreach($device->connectedDevices->all() as $childDevice){
                        $childDevice->parent_device_id = null;
                        $childDevice->save();
                    }
                }
                //eğer değişiklik var ise en son kayıt için update reason güncelleniyor.
                $deviceInfo = $deviceInfo->fresh();
                $deviceInfo?->update(['update_reason' => $deviceValidated['update_reason']]);
                //yeni info ekleniyor.
                 $this->createDeviceInfo($deviceValidated,$device);
            }

            if ($device->isDirty()){
                $device->save();
            }
            return new SuccessResponse('Cihaz Başarı İle Update Edildi.',['data' => $device->id]);
        });
    }
    public function search(Request $request, $type): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = $type::query();

        if ($search) {
            $query->where(function ($query) use ($search) {
                $firstConditionAdded = false;

                if (canView('view-device_name')) {
                    $query->where('device_name', 'like', '%' . $search . '%');
                    $firstConditionAdded = true;
                }
                if (canView('view-type')) {
                    $firstConditionAdded
                        ? $query->orWhere('type', 'like', '%' . $search . '%')
                        : $query->where('type', 'like', '%' . $search . '%');
                    $firstConditionAdded = true;
                }
                if (canView('view-serial_number')) {
                    $firstConditionAdded
                        ? $query->orWhere('serial_number', 'like', '%' . $search . '%')
                        : $query->where('serial_number', 'like', '%' . $search . '%');
                    $firstConditionAdded = true;
                }
                if (canView('view-building') || canView('view-ip_address') || canView('view-description')|| canView('view-unit')) {
                    $query->orWhereHas('latestDeviceInfo.location', function ($q) use ($search, &$firstConditionAdded) {
                        $q->where(function ($q) use ($search, &$firstConditionAdded) {
                            if (canView('view-building')) {
                                $firstConditionAdded
                                    ? $q->orWhere('building', 'like', '%' . $search . '%')
                                    : $q->where('building', 'like', '%' . $search . '%');
                                $firstConditionAdded = true;
                            }

                            if (canView('view-ip_address')) {
                                $firstConditionAdded
                                    ? $q->orWhere('ip_address', 'like', '%' . $search . '%')
                                    : $q->where('ip_address', 'like', '%' . $search . '%');
                                $firstConditionAdded = true;
                            }

                            if (canView('view-description')) {
                                $firstConditionAdded
                                    ? $q->orWhere('description', 'like', '%' . $search . '%')
                                    : $q->where('description', 'like', '%' . $search . '%');
                                $firstConditionAdded = true;
                            }
                            if (canView('view-unit')) {
                                $firstConditionAdded
                                    ? $q->orWhere('unit', 'like', '%' . $search . '%')
                                    : $q->where('unit', 'like', '%' . $search . '%');
                                $firstConditionAdded = true;
                            }
                        });
                    });
                }

                if (canView('view-device_type')) {
                    $query->orWhereHas('deviceType', function ($q) use ($search, &$firstConditionAdded) {
                        $q->where(function($q) use ($search, &$firstConditionAdded) {
                            $firstConditionAdded
                                ? $q->orWhere('model', 'like', '%' . $search . '%')
                                : $q->where('model', 'like', '%' . $search . '%');
                            $q->orWhere('brand', 'like', '%' . $search . '%');
                        });
                        $firstConditionAdded = true;
                    });
                }
            });
        }

        return $query->with('latestDeviceInfo')->sorted()->paginate(10);
    }


    private function createDeviceInfo( $deviceValidated, $device)
    {
        $locationId = Location::getLocationIdFromBuildingAndUnit(
            $deviceValidated['building'] ?? null,
            $deviceValidated['unit'] ?? null
        ) ;

        // Tüm değerlerin null olup olmadığını kontrol et
        $isAllNull = is_null($deviceValidated['ip_address'] ?? null) &&
            is_null($locationId) &&
            is_null($deviceValidated['block'] ?? null) &&
            is_null($deviceValidated['floor'] ?? null) &&
            is_null($deviceValidated['room_number'] ?? null) &&
            is_null($deviceValidated['description'] ?? null);

        if($isAllNull) {

        }
         return DeviceInfo::create([
             'device_id' => $device->id,
             'ip_address' => $deviceValidated['ip_address'] ?? null,
             'location_id' =>$locationId ?? null,
             'block' => $deviceValidated['block'] ?? null,
             'floor' => $deviceValidated['floor'] ?? null,
             'room_number' => $deviceValidated['room_number'] ?? null,
             'description' => $deviceValidated['description'] ?? null,
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
            'device_name' => $deviceValidated['device_name']?? null,
            'serial_number' => $deviceValidated['serial_number'],
            'registry_number' => $deviceValidated['registry_number'],
            'parent_device_id' => $deviceValidated['parent_device_id'] ?? null,
            'parent_device_port' => $deviceValidated['parent_device_port'] ?? null,
            'status' => $deviceValidated['status'] ?? "Depo",
        ];
        return Device::create($attributes);
    }


    private function fillDevice($deviceValidated,$device){

        $deviceType = DeviceType::getDeviceType($deviceValidated['type'],$deviceValidated['model'], $deviceValidated['brand']);

        if($deviceType === null){
            throw new ModelNotFoundException('Cihaz Tipi Bulunamadı!');
        }
        // Device verilerinde değişiklik kontrolü
        $deviceData = ([
            'type' => $deviceType->type,
            'device_type_id' => $deviceType->id,
            'device_name' => $deviceValidated['device_name']?? null,
            'serial_number' => $deviceValidated['serial_number'],
            'registry_number' => $deviceValidated['registry_number'],
            'parent_device_id' => $deviceValidated['parent_device_id']?? null,
            'parent_device_port' => $deviceValidated['parent_device_port']?? null,
            'status' => $deviceValidated['status'] ?? "Depo",


        ]);

        $device->fill($deviceData);

    }

    private function fillDeviceInfo($deviceValidated,$device){

        $locationId = Location::getLocationIdFromBuildingAndUnit(
            $deviceValidated['building'] ?? null,
            $deviceValidated['unit'] ?? null
        ) ?? null;

        $deviceInfo = $device->latestDeviceInfo;
        // DeviceInfo verilerinde değişiklik kontrolü
        $deviceInfoData = [
            'device_id' => $device->id,
            'ip_address' => $deviceValidated['ip_address']?? null,
            'location_id' =>  $locationId ?? null,
            'block' => $deviceValidated['block']?? null,
            'floor' => $deviceValidated['floor']?? null,
            'room_number' => $deviceValidated['room_number']?? null,
        ];

        if($deviceInfo === null){
            $deviceInfo = new DeviceInfo();
        }
        $deviceInfo->fill($deviceInfoData);

        return $deviceInfo;
    }


}
