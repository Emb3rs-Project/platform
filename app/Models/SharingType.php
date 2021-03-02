<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharingType extends Model
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
     * Get the objectInstanceSharingTypes for the sharingType.
     */
    public function objectInstanceSharingTypes(): HasMany
    {
        return $this->hasMany(ObjectInstanceSharingType::class);
    }

    /**
     * Get the simulationManagerSharingTypes for the sharingType.
     */
    public function simulationManagerSharingTypes(): HasMany
    {
        return $this->hasMany(SimulationManagerSharingType::class);
    }
}
