<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationStatus extends Model
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
     * Get the simulationManagers for the simulationStatus.
     */
    public function simulationManagers(): HasMany
    {
        return $this->hasMany(SimulationManager::class);
    }

    /**
     * Get the simulationRuns for the simulationStatus.
     */
    public function simulationRuns(): HasMany
    {
        return $this->hasMany(SimulationRun::class);
    }
}
