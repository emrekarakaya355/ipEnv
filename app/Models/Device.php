<?php

namespace App\Models;

use App\DeviceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model

{
    use HasFactory, SoftDeletes;

    protected $table = 'devices';
    protected $fillable = [
        'device_type_id',
        'type',
        'device_name',
        'serial_number',
        'registry_number',
        'parent_device_id',
        'status',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->id();
        });
    }

    public function newFromBuilder($attributes = [], $connection = null): Device
    {

        $instance = parent::newFromBuilder($attributes, $connection);
        if ($instance->type) {
            $class = self::resolveChildClass($instance->type);
            $instance = (new $class)->newInstance([], true);
            $instance->setRawAttributes((array) $attributes, true);
        }

        return $instance;
    }

    protected static function resolveChildClass($type): string
    {
        $types = [
            'switch' => NetworkSwitch::class,
            'access_point' => AccessPoint::class,
        ];

        return $types[$type] ?? self::class;
    }
    public function deviceInfos(): HasMany
    {
        return $this->hasMany(DeviceInfo::class)
            ->withTrashed()
            ->orderBy('created_at', 'desc');
    }
    public function latestDeviceInfo()
    {
        return $this->hasOne(DeviceInfo::class)->latest();
    }

// Device.php (Model)
    public function latestDeviceLocation()
    {
        return $this->hasOneThrough(
            Location::class,
            DeviceInfo::class,
            'device_id', // DeviceInfo'daki foreign key
            'id',        // Location'daki local key
            'id',        // Device'daki local key
            'location_id' // DeviceInfo'daki foreign key
        )->latest(); // En güncel DeviceInfo'yu alır
    }

    public function deviceType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function getStatusAttribute($value): DeviceStatus
    {
        return DeviceStatus::from($value);
    }

    public function setStatusAttribute($value): void
    {
        if ($value instanceof DeviceStatus) {
            $this->attributes['status'] = $value->value;
        } elseif (is_string($value)) {
            $this->attributes['status'] = DeviceStatus::from($value)->value;
        } else {
            throw new \InvalidArgumentException("Invalid status value.");
        }
    }

    protected function getBuildingAttribute()
    {
        return $this->latestDeviceInfo ? $this->latestDeviceInfo->location->building : null;

    }
    protected function getUnitAttribute()
    {
        return $this->latestDeviceInfo ? $this->latestDeviceInfo->location->unit : null;

    }

    protected function getBlockAttribute()
    {
        return  $this->latestDeviceInfo ? $this->latestDeviceInfo->block : null;
    }

    protected function getFloorAttribute()
    {
        return  $this->latestDeviceInfo ? $this->latestDeviceInfo->floor : null;
    }
    protected function getDescriptionAttribute()
    {
        return  $this->latestDeviceInfo ? $this->latestDeviceInfo->description : null;
    }
    protected function getRoomNumberAttribute()
    {
        return  $this->latestDeviceInfo ? $this->latestDeviceInfo->room_number : null;
    }
    protected function getIpAddressAttribute()
    {

        return $this->latestDeviceInfo ? $this->latestDeviceInfo->ip_address : null;
    }
    protected function getBrandAttribute()
    {
        return $this->deviceType->brand;
    }
    protected function getModelAttribute()
    {
        return $this->deviceType->model;
    }

    public function parentDevice()
    {
        return $this->belongsTo(Device::class, 'parent_device_id');
    }

    public function connectedDevices()
    {
        return $this->hasMany(Device::class, 'parent_device_id');
    }

    // Device.php (Model)
    public function scopeSorted($query, $sortColumn = 'created_at', $sortOrder = 'desc')
    {

        // Sıralama yapılacak sütunlar
        $validColumns = [
            'building' => 'locations.building',
            'unit' => 'locations.unit',
            'block' => 'device_infos.block',
            'floor' => 'device_infos.floor',
            'description' => 'device_infos.description',
            'room_number' => 'device_infos.room_number',
            'ip_address' => 'device_infos.ip_address',
            'brand' => 'device_types.brand',
            'model' => 'device_types.model',
        ];

        $column = $validColumns[$sortColumn] ?? 'created_at';
        // İlişkilendirilmiş tablolarda sıralama yapmak için join işlemi
        if (strpos($column, '.') !== false) {
            list($table, $column) = explode('.', $column);

            if ($table === 'locations') {

                return $query->join('device_infos', 'device_infos.device_id', '=', 'devices.id')
                    ->join('locations', 'locations.id', '=', 'device_infos.location_id')
                    ->orderBy('locations.' . $column, $sortOrder);
            } elseif ($table === 'device_infos') {
                return $query->join('device_infos', 'device_infos.device_id', '=', 'devices.id')
                    ->orderBy('device_infos.' . $column, $sortOrder);
            } elseif ($table === 'device_types') {

                return $query->join('device_types', 'device_types.id', '=', 'devices.device_type_id')
                    ->orderBy('device_types.' . $column, $sortOrder);
            }
        }

        // Diğer sütunlar için doğrudan sıralama
        return $query->orderBy($column, $sortOrder);
    }


    public static function getColumnMapping()
    {
        return [
            'Bina' => 'building',
            'Birim' => 'unit',
            'Cihaz Tipi' => 'type',
            'Marka' => 'brand',
            'Model' => 'model',
            'Seri Numarası' => 'serial_number',
            'Cihaz İsmi' => 'name',
            'IP Adresi' => 'ip_address',
            'Durum' => 'status',
        ];
    }

}
