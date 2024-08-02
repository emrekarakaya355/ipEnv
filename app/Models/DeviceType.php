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

    public function scopeSorted($query)
    {
        return $query->orderBy('type')
            ->orderBy('brand')
            ->orderBy('model');
    }
    public static function getDeviceType($type, $model, $brand)
    {
        // DeviceType bulma
        $deviceType = DeviceType::where('type', $type)
            ->where('model', $model)
            ->where('brand', $brand)
            ->first();

        if($deviceType === null){
            return response()->json(['false' => false]);
        }
        return $deviceType;
    }
}
