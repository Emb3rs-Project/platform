<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationModuleFunction extends Model
{
    use HasFactory, SoftDeletes;


    public function module(): BelongsTo
    {
        return $this->belongsTo(SimulationModule::class, 'simulation_module_id');
    }
}
