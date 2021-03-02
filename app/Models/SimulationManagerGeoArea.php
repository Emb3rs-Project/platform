<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationManagerGeoArea extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the simulationManager that owns the simulationManagerGeoArea.
     */
    public function simulationManager(): BelongsTo
    {
        return $this->belongsTo(SimulationManager::class);
    }

    /**
     * Get the geoArea that owns the simulationManagerGeoArea.
     */
    public function geoArea(): BelongsTo
    {
        return $this->belongsTo(GeoArea::class);
    }
}
