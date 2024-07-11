<?php

namespace App\Models;

use App\Scopes\DeviceTypeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NetworkSwitch extends Device
{
    use HasFactory;

    protected $table = 'devices';
    protected $fillable = ['type', 'brand'];

    protected static function boot()
    {

        parent::boot();

        static::addGlobalScope(new DeviceTypeScope('switch'));

        static::creating(function ($model) {
            $model->type = 'switch';
        });
    }
    public function parentSwitch()
    {
        return $this->belongsTo(NetworkSwitch::class,'parent_switch_id');
    }



}
