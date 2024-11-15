<?php

namespace App\Models;

use App\Scopes\DeviceTypeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kgs extends Device
{
    use HasFactory;
    protected $table = 'devices';
    protected static function boot()
    {

        parent::boot();

        static::addGlobalScope(new DeviceTypeScope('kgs'));

        static::creating(function ($model) {
            $model->type = 'kgs';
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

