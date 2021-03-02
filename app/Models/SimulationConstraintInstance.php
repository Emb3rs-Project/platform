<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationConstraintInstance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the simulationManager that owns the simulationConstraintInstance.
     */
    public function simulationManager(): BelongsTo
    {
        return $this->belongsTo(SimulationManager::class);
    }

    /**
     * Get the simulationConstraint that owns the simulationConstraintInstance.
     */
    public function simulationConstraint(): BelongsTo
    {
        return $this->belongsTo(SimulationConstraint::class);
    }

    /**
     * Get the unit that owns the simulationConstraintInstance.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
