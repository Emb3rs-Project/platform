<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectInstance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the user that owns the objectInstance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the objectTemplate that owns the objectInstance.
     */
    public function objectTemplate(): BelongsTo
    {
        return $this->belongsTo(ObjectTemplate::class);
    }

    /**
     * Get the objectInstanceSharingTypes for the objectInstance.
     */
    public function objectInstanceSharingTypes(): HasMany
    {
        return $this->hasMany(ObjectInstanceSharingType::class);
    }

    /**
     * Get the SimulationManagerObjectInstance for the objectInstance.
     */
    public function SimulationManagerObjectInstances(): HasMany
    {
        return $this->hasMany(SimulationManagerObjectInstance::class);
    }
}
