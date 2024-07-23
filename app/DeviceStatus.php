<?php

namespace App;

enum DeviceStatus: string
{
    case WORKING = 'Çalışıyor';
    case STORAGE = 'Depo';
    case WARRANTY = 'Garanti';
    case SCRAP = 'Hurda';

    public static function toArray(): array
    {
        return [
            self::WORKING->value => 'Çalışıyor',
            self::STORAGE->value => 'Depo',
            self::WARRANTY->value => 'Garanti',
            self::SCRAP->value => 'Hurda',
        ];
    }
}
