<?php

namespace App\Imports;

use App\Models\Device;
use Illuminate\Support\Facades\DB;

class DeviceParentImport extends  BaseImport
{
    public function rules(): array
    {
        return [
            'mac_address' => [
                'required',
                'string',
                'regex:/^([0-9A-Fa-f]{2}([-:])?){5}[0-9A-Fa-f]{2}$/', // Ensures MAC address format
            ],

            'ip_address' => [
                'nullable',
                'ipv4',
            ],
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
        $device = Device::where('mac_address', $row['mac_address'])->first();
        //Cihaz yoksa fail olsun
        if(!$device){
            $this->fail($row,(array)'Bu mac adresine ait Cihaz bulunamadı');
        }
        $deviceInfo = $device->latestDeviceInfo;

        $parentDevice = Device::whereHas('latestDeviceInfo', function ($query) use ($row) {
            $query->where('ip_address', $row['parent_ip_address']);
        })->first();
        try {
            DB::beginTransaction();
            $deviceData = ([
                'parent_device_id' => $parentDevice->id?? null,
                'parent_device_port' => trim($row['parent_device_port'])?? null,
            ]);
            $deviceInfoData =([
                'ip_address' => trim($row['ip_address'])?? null,
            ]);
            $device->fill($deviceData);
            $deviceInfo->fill($deviceInfoData);
            if($device->isDirty()) {
                $device->save();
            }
            if($deviceInfo->isDirty()) {
                $deviceInfo->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->fail($row, (array)$e->getMessage());
        }
    }
}
