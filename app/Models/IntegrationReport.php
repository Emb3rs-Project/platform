<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegrationReport extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'data',
        'type',
        'errors',
        'output',
        'step_uuid',
        'module',
        'function',
        'simulation_id',
        'simulation_uuid'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'errors' => 'array',
    ];

    /**
     * The Simulation Metadata that this Integration Result belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function simulationMetadata(): BelongsTo
    {
        return $this->belongsTo(SimulationMetadata::class, 'simulation_metadata_id');
    }

    /**
     * The Simulation that this Integration Result belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function simulation(): BelongsTo
    {
        return $this->belongsTo(Simulation::class, 'simulation_id');
    }
}
