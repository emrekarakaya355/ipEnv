<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model

{
    use HasFactory;

    protected $table = 'devices';

    public function newFromBuilder($attributes = [], $connection = null)
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
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }
}
