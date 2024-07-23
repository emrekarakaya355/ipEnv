<?php

namespace App\Models;

use App\DeviceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model

{
    use HasFactory, SoftDeletes;

    protected $table = 'devices';
    protected $fillable = [
        'device_type_id',
        'type',
        'device_name',
        'serial_number',
        'registry_number',
        'parent_device_id',
        'status',
    ];
    public function newFromBuilder($attributes = [], $connection = null): Device
    {

        $instance = parent::newFromBuilder($attributes, $connection);
        if ($instance->type) {
            $class = self::resolveChildClass($instance->type);
            $instance = (new $class)->newInstance([], true);
            $instance->setRawAttributes((array) $attributes, true);
        }

        return $instance;
    }

    protected static function resolveChildClass($type): string
    {
        $types = [
            'switch' => NetworkSwitch::class,
            'access_point' => AccessPoint::class,
        ];

        return $types[$type] ?? self::class;
    }
    public function deviceInfos()
    {
        return $this->hasMany(DeviceInfo::class);
    }
    public function latestDeviceInfo()
    {
        return $this->hasOne(DeviceInfo::class)->latest();
    }

    public function deviceType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function getStatusAttribute($value): DeviceStatus
    {
        return DeviceStatus::from($value);
    }

    public function setStatusAttribute($value): void
    {
        if ($value instanceof DeviceStatus) {
            $this->attributes['status'] = $value->value;
        } elseif (is_string($value)) {
            $this->attributes['status'] = DeviceStatus::from($value)->value;
        } else {
            throw new \InvalidArgumentException("Invalid status value.");
        }
    }

    protected function getFacultyAttribute()
    {
        return $this->latestDeviceInfo ? $this->latestDeviceInfo->location->faculty : null;

    }

    protected function getBlockAttribute()
    {
        return  $this->latestDeviceInfo ? $this->latestDeviceInfo->block : null;
    }

    protected function getFloorAttribute()
    {
        return  $this->latestDeviceInfo ? $this->latestDeviceInfo->floor : null;
    }
    protected function getDescriptionAttribute()
    {
        return  $this->latestDeviceInfo ? $this->latestDeviceInfo->description : null;
    }
    protected function getRoomNumberAttribute()
    {
        return  $this->latestDeviceInfo ? $this->latestDeviceInfo->room_number : null;
    }
    protected function getIpAddressAttribute()
    {

        return $this->latestDeviceInfo ? $this->latestDeviceInfo->ip_address : null;
    }
    protected function getBrandAttribute()
    {
        return $this->deviceType->brand;
    }
    protected function getModelAttribute()
    {
        return $this->deviceType->model;
    }

    public function parentDevice()
    {
        return $this->belongsTo(Device::class, 'parent_device_id');
    }

    public function connectedDevices()
    {
        return $this->hasMany(Device::class, 'parent_device_id');
    }



    public function scopeSorted($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

}
