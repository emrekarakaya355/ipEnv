<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['building', 'unit'];

    /**
     * DeviceInfo ile ilişkisi
     */
    public function deviceInfo()
    {
        return $this->hasMany(DeviceInfo::class);
    }

    /**
     * Bina adına göre sıralı sorgu kapsamı
     */
    public function scopeSorted($query)
    {
        return $query->orderBy('building', 'asc');
    }

    /**
     * Belirli bir bina için benzersiz üniteleri döndürür
     *
     * @param string $building
     * @return \Illuminate\Support\Collection
     */
    public static function getUnitsByBuilding($building)
    {
        return static::where('building', $building)->distinct()->pluck('unit');
    }

    /**
     * Bina ve ünite bilgisine göre location ID'sini döndürür
     *
     * @param string|null $building
     * @param string|null $unit
     * @return int
     */
    public static function getLocationIdFromBuildingAndUnit($building, $unit)
    {
        if ($building === null) {
            return 1;
        }

        if ($unit === null) {
            $location = static::where('building', $building)->first();
            return $location === null ? 1 : $location->id;
        }

        $location = static::where('building', $building)
            ->where('unit', $unit)
            ->first();

        return $location === null ? 1 : $location->id;
    }
}
