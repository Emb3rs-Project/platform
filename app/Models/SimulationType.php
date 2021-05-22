<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationType extends Model
{
    use  SoftDeletes;

    // Table simulations
    public function simulations(): HasMany
    {
        return $this->hasMany(Simulation::class, 'simulation_type_id');
    }

    // Table simulation_types
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'default_unit_id');
    }
}
