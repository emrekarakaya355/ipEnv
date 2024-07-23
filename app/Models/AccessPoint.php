<?php

namespace App\Models;

use App\Scopes\DeviceTypeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccessPoint extends Device
{
    use HasFactory;
    protected $table = 'devices';
    protected static function boot()
    {

        parent::boot();

        static::addGlobalScope(new DeviceTypeScope('access_point'));

        static::creating(function ($model) {
            $model->type = 'access_point';
        });


    }
    public function deviceInfos(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DeviceInfo::class, 'device_id');
    }

    public function latestDeviceInfo()
    {
        return $this->hasOne(DeviceInfo::class,'device_id')->latest();
    }
}

