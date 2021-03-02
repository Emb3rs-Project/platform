<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationRun extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parameter',
        'value',
    ];

    /**
     * Get the simulationManager that owns the simulationRun.
     */
    public function simulationManager(): BelongsTo
    {
        return $this->belongsTo(SimulationManager::class);
    }

    /**
     * Get the simulationStatus that owns the simulationRun.
     */
    public function simulationStatus(): BelongsTo
    {
        return $this->belongsTo(SimulationStatus::class);
    }
}
