<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationTypeInstance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the simulationManager that owns the simulationTargetInstance.
     */
    public function simulationManager(): BelongsTo
    {
        return $this->belongsTo(SimulationManager::class);
    }

    /**
     * Get the simulationConstraint that owns the simulationTargetInstance.
     */
    public function simulationType(): BelongsTo
    {
        return $this->belongsTo(SimulationType::class);
    }

    /**
     * Get the unit that owns the simulationTargetInstance.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
