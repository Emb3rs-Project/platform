<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeoPoint extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'latitude',
        'longitude',
    ];

    /**
     * Get the geoPointGeoAreas for the geoPoint.
     */
    public function geoAreas(): BelongsToMany
    {
        return $this->belongsToMany(
            GeoArea::class,
            'geo_point_geo_areas',
            'geo_point_id',
            'geo_area_id'
        );
    }
}
