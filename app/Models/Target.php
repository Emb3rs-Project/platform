<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Target extends Model
{
    use HasFactory, SoftDeletes;


    public function simulations() : HasMany
    {
        return $this->hasMany(Simulation::class, 'target_id');
    }

    public function units(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'default_unit_id');
    }
}
