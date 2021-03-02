<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationTargetInstance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the simulationManagers for the simulationTargetInstance.
     */
    public function simulationManagers(): HasMany
    {
        return $this->hasMany(SimulationManager::class);
    }

    /**
     * Get the simulationConstraint that owns the simulationTargetInstance.
     */
    public function simulationTarget(): BelongsTo
    {
        return $this->belongsTo(SimulationTarget::class);
    }

    /**
     * Get the unit that owns the simulationTargetInstance.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
