<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    protected $fillable = ['name', 'script'];

    public function deviceTypes()
    {
        return $this->belongsToMany(DeviceType::class, 'script_device_type', 'script_id', 'device_type_id');
    }
}
