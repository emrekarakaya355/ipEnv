<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['faculty'];


    public function deviceInfo()
    {
        return $this->hasMany(DeviceInfo::class);
    }


    // Define the sorted scope
    public function scopeSorted($query)
    {
        return $query->orderBy('faculty', 'asc'); // Example sorting by creation date
    }
}
