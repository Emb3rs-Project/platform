<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeoObject extends Model
{
    use HasFactory, SoftDeletes;

    // Table locations
    public function locations() : HasMany
    {
        return $this->hasMany(Location::class, 'geo_object_id');
    }
}
