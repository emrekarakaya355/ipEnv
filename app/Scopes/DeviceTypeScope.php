<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DeviceTypeScope implements Scope
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('type', $this->type);
    }

}
