<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case VIEW_USERS = 'view-users';
    case CREATE_USERS = 'create-users';
    case EDIT_USERS = 'edit-users';
    case DELETE_USERS = 'delete-users';

    case VIEW_DEVICES = 'view-devices';
    case CREATE_DEVICES = 'create-devices';
    case EDIT_DEVICES = 'edit-devices';
    case DELETE_DEVICES = 'delete-devices';

    case VIEW_LOCATIONS = 'view-locations';
    case CREATE_LOCATIONS = 'create-locations';
    case EDIT_LOCATIONS = 'edit-locations';
    case DELETE_LOCATIONS = 'delete-locations';

    case VIEW_DEVICE_TYPES = 'view-device-types';
    case CREATE_DEVICE_TYPES = 'create-device-types';
    case EDIT_DEVICE_TYPES = 'edit-device-types';
    case DELETE_DEVICE_TYPES = 'delete-device-types';

    case VIEW_CONNECTED_DEVICES = 'view-connected-devices';

    case VIEW_IP_ADDRESS = 'view-ip-address';



    public function label(): string
    {
        return match ($this) {
            static::VIEW_USERS => 'Kullanıcıları Görüntüle',
            static::CREATE_USERS => 'Kullanıcı Oluştur',
            static::EDIT_USERS => 'Kullanıcı Düzenle',
            static::DELETE_USERS => 'Kullanıcıyı Sil',
            static::VIEW_DEVICES => 'Cihazları Görüntüle',
            static::CREATE_DEVICES => 'Cihaz Oluştur',
            static::EDIT_DEVICES => 'Cihaz Düzenle',
            static::DELETE_DEVICES => 'Cihazı Sil',
            static::VIEW_LOCATIONS => 'Lokasyonları Görüntüle',
            static::CREATE_LOCATIONS => 'Lokasyon Oluştur',
            static::EDIT_LOCATIONS => 'Lokasyonu Düzenle',
            static::DELETE_LOCATIONS => 'Lokasyonu Sil',
            static::VIEW_DEVICE_TYPES => 'Cihaz Tiplerini Görüntüle',
            static::CREATE_DEVICE_TYPES => 'Cihaz Tipi Oluştur',
            static::EDIT_DEVICE_TYPES => 'Cihaz Tipini Düzenle',
            static::DELETE_DEVICE_TYPES => 'Cihaz Tipini Sil',
        };
    }
}
