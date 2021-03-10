<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class);
    }

    public function geoObject(): BelongsTo
    {
        return $this->belongsTo(GeoObject::class, 'geo_object_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'location_id')
    }
}
