<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, hasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Kullanıcının oluşturduğu DeviceInfo kayıtları
    public function createdDeviceInfos()
    {
        return $this->hasMany(DeviceInfo::class, 'created_by');
    }

    // Kullanıcının güncellediği DeviceInfo kayıtları
    public function updatedDeviceInfos()
    {
        return $this->hasMany(DeviceInfo::class, 'updated_by');
    }

    // Kullanıcının sildiği (soft delete) DeviceInfo kayıtları
    public function deletedDeviceInfos()
    {
        return $this->hasMany(DeviceInfo::class, 'deleted_by');
    }
    // Kullanıcının oluşturduğu DeviceInfo kayıtları
    public function createdDevice()
    {
        return $this->hasMany(Device::class, 'created_by');
    }

    // Kullanıcının güncellediği DeviceInfo kayıtları
    public function updatedDevice()
    {
        return $this->hasMany(Device::class, 'updated_by');
    }

    // Kullanıcının sildiği (soft delete) DeviceInfo kayıtları
    public function deletedDevice()
    {
        return $this->hasMany(Device::class, 'deleted_by');
    }


}
