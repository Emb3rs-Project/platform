<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationManager extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'version',
    ];

    /**
     * Get the user that owns the simulationManager.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the simulationStatus that owns the simulationManager.
     */
    public function simulationStatus(): BelongsTo
    {
        return $this->belongsTo(SimulationStatus::class);
    }

    /**
     * Get the simulationOutputs for the simulationManager.
     */
    public function simulationOutputs(): HasMany
    {
        return $this->hasMany(SimulationOutput::class);
    }

    /**
     * Get the simulationRuns for the simulationManager.
     */
    public function simulationRuns(): HasMany
    {
        return $this->hasMany(SimulationRun::class);
    }

    /**
     * Get the simulationManagerGeoAreas for the simulationManager.
     */
    public function simulationManagerGeoAreas(): HasMany
    {
        return $this->hasMany(SimulationManagerGeoArea::class);
    }

    /**
     * Get the simulationManagerObjectInstances for the simulationManager.
     */
    public function simulationManagerObjectInstances(): HasMany
    {
        return $this->hasMany(SimulationManagerObjectInstance::class);
    }

    /**
     * Get the simulationConstraintInstances for the simulationManager.
     */
    public function simulationConstraintInstances(): HasMany
    {
        return $this->hasMany(SimulationConstraintInstance::class);
    }

    /**
     * Get the simulationTargetInstance that owns the simulationManager.
     */
    public function simulationTargetInstance(): BelongsTo
    {
        return $this->belongsTo(SimulationTargetInstance::class);
    }

    /**
    * Get the simulationTypeInstance that owns the simulationManager.
    */
    public function simulationTypeInstance(): BelongsTo
    {
        return $this->belongsTo(SimulationTypeInstance::class);
    }

    /**
     * Get the simulationManagerSharingTypes for the simulationManager.
     */
    public function simulationManagerSharingTypes(): HasMany
    {
        return $this->hasMany(SimulationManagerSharingType::class);
    }
}
