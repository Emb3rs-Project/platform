<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'symbol',
        'quantity',
    ];

    /**
     * Get the SimulationConstraint for the unit.
     */
    public function simulationConstraints(): HasMany
    {
        return $this->hasMany(SimulationConstraint::class);
    }

    /**
     * Get the simulationTarget for the unit.
     */
    public function simulationTargets(): HasMany
    {
        return $this->hasMany(SimulationTarget::class);
    }

    /**
     * Get the simulationType for the unit.
     */
    public function simulationTypes(): HasMany
    {
        return $this->hasMany(SimulationType::class);
    }

    /**
     * Get the simulationConstraintInstances for the unit.
     */
    public function simulationConstraintInstances(): HasMany
    {
        return $this->hasMany(SimulationConstraintInstance::class);
    }

    /**
     * Get the simulationTargetInstances for the unit.
     */
    public function simulationTargetInstances(): HasMany
    {
        return $this->hasMany(SimulationTargetInstance::class);
    }

    /**
     * Get the simulationTargetInstances for the unit.
     */
    public function simulationTypeInstances(): HasMany
    {
        return $this->hasMany(SimulationTypeInstance::class);
    }

    /**
     * Get the simulation for the unit as Source.
     */
    public function conversionAsSource(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'source_unit_id');
    }

    /**
     * Get the conversion for the unit as Target.
     */
    public function conversionAsTarget(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'target_unit_id');
    }
}
