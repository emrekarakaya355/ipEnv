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
    public function parentSwitch()
    {
        return $this->belongsTo(NetworkSwitch::class,'parent_switch_id');
    }
}

