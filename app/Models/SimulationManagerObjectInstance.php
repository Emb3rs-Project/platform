<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationManagerObjectInstance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the simulationManager that owns the simulationManagerObjectInstance.
     */
    public function simulationManager(): BelongsTo
    {
        return $this->belongsTo(SimulationManager::class);
    }

    /**
     * Get the objectInstance that owns the simulationManagerObjectInstance.
     */
    public function objectInstance(): BelongsTo
    {
        return $this->belongsTo(ObjectInstance::class);
    }
}
