<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeoArea extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the user that owns the geoArea.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the region for the geoArea.
     */
    public function region(): HasOne
    {
        return $this->hasOne(Region::class);
    }

    /**
     * Get the country for the geoArea.
     */
    public function country(): HasOne
    {
        return $this->hasOne(Country::class);
    }

    /**
     * Get the simulationManagerGeoAreas for the simulationManager.
     */
    public function simulationManagerGeoAreas(): HasMany
    {
        return $this->hasMany(SimulationManagerGeoArea::class);
    }

    public function geoPoints(): BelongsToMany
    {
        return $this->belongsToMany(
            GeoPoint::class,
            'geo_point_geo_areas',
            'geo_area_id',
            'geo_point_id'
        );
    }
}
