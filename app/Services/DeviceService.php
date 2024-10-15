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

    public function filter(Request $request,$query){
           $query
           ->when(request('device_name'), function ($query) {
               if (canView('view-device_name')) {
                   return $query->where('device_name', 'like', '%' . request('device_name') . '%');
               }
               return $query;
           })
               ->when(request('type'), function ($query) {
                   if (canView('view-type')) {
                       return $query->where('type', 'like', '%' . request('type') . '%');
                   }
                   return $query;
               })
               ->when(request('serial_number'), function ($query) {
                   if (canView('view-serial_number')) {
                       return $query->where('serial_number', 'like', '%' . request('serial_number') . '%');
                   }
                   return $query;
               })
               ->when(request('registry_number'), function ($query) {
                   if (canView('view-registry_number')) {
                       return $query->where('registry_number', 'like', '%' . request('registry_number') . '%');
                   }
                   return $query;
               })
               ->when(request('status'), function ($query) {
                   if (canView('view-status')) {
                       return $query->where('status', 'like', '%' . request('status') . '%');
                   }
                   return $query;
               })

               ->when(request('building') || request('ip_address') || request('description') || request('unit'), function ($query) {
                   if (canView('view-building') || canView('view-ip_address') || canView('view-description') || canView('view-unit')) {
                       return $query->WhereHas('latestDeviceInfo.location', function ($q) {
                           $q
                               ->when(canView('view-building') && request('building'), function ($q) {
                               return $q->where('building', 'like', '%' . request('building') . '%');
                           })
                               ->when(canView('view-ip_address') && request('ip_address'), function ($q) {
                                   return $q->Where('ip_address', 'like', '%' . request('ip_address') . '%');
                               })
                               ->when(canView('view-description') && request('description'), function ($q) {
                                   return $q->Where('description', 'like', '%' . request('description') . '%');
                               })
                               ->when(canView('view-unit') && request('unit'), function ($q) {
                                   return $q->Where('unit', 'like', '%' . request('unit') . '%');
                               });
                       });
                   }
                   return $query;
               })
               ->when(request('model') || request('brand'), function ($query) {
                   if (canView('view deviceType')) {
                       return $query->WhereHas('deviceType', function ($q) {
                           $q->when(request('model'), function ($q) {
                               return $q->where('model', 'like', '%' . request('model') . '%');
                           })
                               ->when(request('brand'), function ($q) {
                                   return $q->Where('brand', 'like', '%' . request('brand') . '%');
                               });
                       });
                   }
                   return $query;
               });

                   /*
            $query->where(function ($query) use ($request) {
                $firstConditionAdded = false;
                if (canView('view-device_name')) {
                    $query->where('device_name', 'like', '%' . request('device_name') . '%');
                    $firstConditionAdded = true;
                }
                if (canView('view-type')) {
                    $firstConditionAdded
                        ? $query->orWhere('type', 'like', '%' . request('type') . '%')
                        : $query->where('type', 'like', '%' . request('type') . '%');
                    $firstConditionAdded = true;
                }
                if (canView('view-serial_number')) {
                    $firstConditionAdded
                        ? $query->orWhere('serial_number', 'like', '%' . request('serial_number') . '%')
                        : $query->where('serial_number', 'like', '%' . request('serial_number') . '%');
                    $firstConditionAdded = true;
                }
                if (canView('view-building') || canView('view-ip_address') || canView('view-description')|| canView('view-unit')) {
                    $query->orWhereHas('latestDeviceInfo.location', function ($q) use ($query
                        , &$firstConditionAdded) {
                        $q->where(function ($q) use ($query, &$firstConditionAdded) {
                            if (canView('view-building')) {
                                $firstConditionAdded
                                    ? $q->orWhere('building', 'like', '%' . request('building') . '%')
                                    : $q->where('building', 'like', '%' . request('building') . '%');
                                $firstConditionAdded = true;
                            }

                            if (canView('view-ip_address')) {
                                $firstConditionAdded
                                    ? $q->orWhere('ip_address', 'like', '%' . request('ip_address') . '%')
                                    : $q->where('ip_address', 'like', '%' . request('ip_address') . '%');
                                $firstConditionAdded = true;
                            }

                            if (canView('view-description')) {
                                $firstConditionAdded
                                    ? $q->orWhere('description', 'like', '%' . request('description') . '%')
                                    : $q->where('description', 'like', '%' . request('description') . '%');
                                $firstConditionAdded = true;
                            }
                            if (canView('view-unit')) {
                                $firstConditionAdded
                                    ? $q->orWhere('unit', 'like', '%' . request('unit') . '%')
                                    : $q->where('unit', 'like', '%' . request('unit') . '%');
                                $firstConditionAdded = true;
                            }
                        });
                    });
                }

                if (canView('view-device_type')) {
                    $query->orWhereHas('deviceType', function ($q) use ($search, &$firstConditionAdded) {
                        $q->where(function($q) use ($search, &$firstConditionAdded) {
                            $firstConditionAdded
                                ? $q->orWhere('model', 'like', '%' . request('model') . '%')
                                : $q->where('model', 'like', '%' . request('model') . '%');
                            $q->orWhere('brand', 'like', '%' . request('brand') . '%');
                        });
                        $firstConditionAdded = true;
                    });
                }
            });*/
    }

    public function search(Request $request, $query)
    {
        $search = $request->input('search');

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
