<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'device_id',
        'location_id',
        'ip_address',
        'update_reason',
        'status',
        'block',
        'floor',
        'description',
        'room_number',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }


    // Varsayılan değerlerle oluşturma metodu
    public static function createDefault($deviceId)
    {
        $defaultDeviceInfo = [
            'ip_address' => 'N/A',
            'location_id' => 1,
            'block' => null,
            'floor' => null,
            'room_number' => null,
            'description' => 'Cihaz depoya çekildi.'
        ];

        return self::create(array_merge(['device_id' => $deviceId], $defaultDeviceInfo));
    }

}

