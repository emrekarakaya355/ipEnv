<?php

namespace App\Models;

use App\Exceptions\ConflictException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class DeviceInfo extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;

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

    public static function createDefault($deviceId)
    {
        $locationId = Location::getLocationIdFromBuildingAndUnit(
            'Rektörlük',
            'Depo'
        );
        $defaultDeviceInfo = [
            'ip_address' => null,
            'location_id' => $locationId,
            'block' => null,
            'floor' => null,
            'room_number' => null,
            'description' => 'Default Kayıt'
        ];
        return self::create(array_merge(['device_id' => $deviceId], $defaultDeviceInfo));
    }

    protected static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            // Aynı device_id'ye sahip mevcut kayıtları bul ve sil
            $oldRecords = self::where('device_id', $model->device_id)->get();
            foreach ($oldRecords as $record) {
                $record->delete(); // Her kayıt için deleting event'i tetiklenir
            }

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
        static::restoring(function ($model) {
            $model->deleted_by = null;
            $model->isDeleted = false;
            $model->updated_by = auth()->id();

        });


        static::saving(function ($model) {
            // Aynı IP adresine sahip silinmemiş (soft deleted olmayan) bir kayıt olup olmadığını kontrol et
            $existingDevice = static::where('ip_address', $model->ip_address)
                ->where('ip_address', '!=', null)
                ->whereNull('deleted_at')
                ->where('device_id', '!=', $model->device_id) // device_id eşit değil
                ->exists();
            // Eğer böyle bir kayıt varsa, kaydetme işlemini engelle
            if ($existingDevice) {
                throw new ConflictException('Girdiğiniz ip adresi başka cihaz tarafından kullanılıyor.');
            }
            return true;
        });
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    // DeviceInfo'yu oluşturan kullanıcı

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // DeviceInfo'yu güncelleyen kullanıcı

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // DeviceInfo'yu silen kullanıcı

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Varsayılan değerlerle oluşturma metodu

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }


    public function scopeSorted($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

}

