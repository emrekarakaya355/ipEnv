<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    use HasFactory;

    protected $table = 'device_types';
    protected $fillable = ['type', 'brand','model'];

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
    public static function getBrandsByType($type)
    {
        return static::where('type', $type)->distinct('brand')->pluck('brand');
    }
    public static function getModelsByBrand($type, $brand)
    {
        return static::where('type', $type)->where('brand', $brand)->pluck('model');
    }

}
