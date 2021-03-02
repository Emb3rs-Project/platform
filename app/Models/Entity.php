<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the userEntities for the entity.
     */
    public function userEntities(): HasMany
    {
        return $this->hasMany(UserEntity::class);
    }

    /**
     * Get the objectInstanceSharingTypes for the entity.
     */
    public function objectInstanceSharingTypes(): HasMany
    {
        return $this->hasMany(ObjectInstanceSharingType::class);
    }

    /**
     * Get the simulationManagerSharingTypes for the entity.
     */
    public function simulationManagerSharingTypes(): HasMany
    {
        return $this->hasMany(SimulationManagerSharingType::class);
    }
}
