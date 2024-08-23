<?php

namespace App\Models;

use App\Enums\DeviceStatus;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Device extends Model implements Auditable

{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'devices';
    protected $fillable = [
        'device_type_id',
        'type',
        'device_name',
        'serial_number',
        'registry_number',
        'parent_device_id',
        'status',
        'parent_device_port',
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
            $model->isDeleted = true;
            $model->save();
        });

        static::deleted(function ($model) {
            if($model->latestDeviceInfo){
                $model->latestDeviceInfo->delete();
            }
        });

        static::restoring(function ($model) {
            $model->deleted_by = null;
            $model->isDeleted = false;
            $model->updated_by = auth()->id();

        });


        static::saving(function ($model) {
            if ($model->parent_device_id) {
                if (!$model->parent_device_port) {
                    throw new \Exception('Port numarasını girmek zorundasınız.');
                }
                $parentDevice = self::find($model->parent_device_id);

                if($parentDevice === null) return;

                if ( $parentDevice->deviceType->type == 'access_point') {
                    throw new \Exception('Access Point parent olarak seçilemez');
                }
                if ($model->parent_device_port > $parentDevice->deviceType->port_number) {
                    throw new \Exception('Seçtiğiniz cihaz '.$parentDevice->deviceType->port_number.' adet porta sahiptir. Lütfen bu sayıdan küçük bir değer giriniz.');
                }
                foreach ($parentDevice->connectedDevices as $connectedDevice) {
                   if ($connectedDevice->parent_device_port == $model->parent_device_port && $connectedDevice->id != $model->id ) {
                       throw new \Exception('Bu port '.$connectedDevice->latest_deviced_info->ip_address.' ip adresine sahip cihaz tarafından kullanılıyor');
                   }
                }
            }
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

    // Device oluşturan kullanıcı
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Device güncelleyen kullanıcı
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // DeviceInfo silen kullanıcı
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
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
    protected function getPortNumberAttribute()
    {
        return $this->deviceType->port_number;
    }
    public function parentDevice()
    {
        return $this->belongsTo(Device::class, 'parent_device_id');
    }

    public function connectedDevices()
    {
        return $this->hasMany(Device::class, 'parent_device_id');
    }

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

    /**
     * @throws Exception
     */
    public static function create(array $attributes)
    {

    //Try catch device servicede düşüyor
            // Silinmiş bir kaydı kontrol et
        $existingModel = static::withTrashed()
            ->where('type', $attributes['type'])
            ->where('device_type_id', $attributes['device_type_id'])
            ->where('serial_number', $attributes['serial_number'])
            ->where('registry_number',$attributes['registry_number'])->first();

        if ($existingModel ) {
            if( $existingModel->trashed()){
                // Kayıt silinmişse, restore et ve güncelle
                   $existingModel->restore();
                   $existingModel->update($attributes);
                   return $existingModel;
            }
        }
        return static::query()->create($attributes);
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
