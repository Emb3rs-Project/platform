<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationModule extends Model
{
    use HasFactory, SoftDeletes;


    public function functions(): HasMany
    {
        return $this->hasMany(SimulationModuleFunction::class, 'simulation_module_id');
    }
}
