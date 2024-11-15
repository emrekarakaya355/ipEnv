<?php

namespace App\Enums;

enum DeviceStatus: string
{
    case WORKING = 'Çalışıyor';
    case STORAGE = 'Depo';
    case WARRANTY = 'Garanti';
    case SCRAP = 'Hurda';

    public static function toArray(): array
    {
        return [
            self::WORKING->name => self::WORKING->value,
            self::STORAGE->name => self::STORAGE->value,
            self::WARRANTY->name => self::WARRANTY->value,
            self::SCRAP->name => self::SCRAP->value,
        ];
    }
    public static function fromName(string $name): self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new \InvalidArgumentException("Invalid status name: $name");
    }
}
