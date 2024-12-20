<?php

namespace App\Models;

use App\Exceptions\ConflictException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Location extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['building', 'unit'];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
        static::saving(function ($model) {
            // Var olan bir kaydı kontrol et (unique validation)
            $existingModel = Location::where([
                ['building', '=', $model->building],
                ['unit', '=', $model->unit],
            ])->first();

            if ($existingModel && $existingModel->id !== $model->id) {
                throw new ConflictException("Yer Bilgisi Zaten Var!");
            }
        });

    }
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


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
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


    public static function getColumnMapping()
    {
        return [
            'Bina' => 'building',
            'Birim' => 'unit',
        ];
    }
}
