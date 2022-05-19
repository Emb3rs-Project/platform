<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationModuleFunctionProperty extends Model
{
    use HasFactory, SoftDeletes;


    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, "property_id");
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, "unit_id");
    }

    public function simulation_functions(): BelongsTo
    {
        return $this->belongsTo(SimulationModuleFunction::class, 'simulation_module_function_id');
    }
}
