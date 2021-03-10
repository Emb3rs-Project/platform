<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Simulation extends Model
{
    use HasFactory, SoftDeletes;

    public function project() : BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function target() : BelongsTo
    {
        return $this->belongsTo(Target::class, 'target_id');
    }

    public function simulationType() : BelongsTo
    {
        return $this->belongsTo(SimulationType::class, 'simulation_type_id');
    }
}
