<?php

namespace App\Enums;

enum RolesEnum: string
{

    case SUPER_ADMIN = 'super-admin';
    case ADMIN= 'admin';
    case EDITOR= 'editor';
    case READER = 'reader';

    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            static::SUPER_ADMIN => 'Süper Admin',
            static::ADMIN => 'Admin',
            static::EDITOR => 'Editor',
            static::READER => 'Yetkisiz Kullanıcı',
        };
    }
}
