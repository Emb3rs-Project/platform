<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
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
     * Get the geoArea that owns the country.
     */
    public function geoArea(): BelongsTo
    {
        return $this->belongsTo(GeoArea::class);
    }

    /**
     * Get the regions for the country.
     */
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    /**
     * Get the userRegistrationRequests for the country.
     */
    public function userRegistrationRequests(): HasMany
    {
        return $this->hasMany(UserRegistrationRequest::class);
    }
}
