<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'description',
    ];

    /**
     * Get the geoArea that owns the region.
     */
    public function geoArea()
    {
        return $this->belongsTo(GeoArea::class);
    }

    /**
     * Get the country that owns the region.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the userRegistrationRequests for the region.
     */
    public function userRegistrationRequests(): HasMany
    {
        return $this->hasMany(UserRegistrationRequest::class);
    }
}
