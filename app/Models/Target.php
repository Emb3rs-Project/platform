<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Target extends Model
{
    use SoftDeletes;

    /**
     * The Simulations that this Target belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function simulations(): HasMany
    {
        return $this->hasMany(Simulation::class, 'target_id');
    }

    /**
     * The Unit that this Target belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'default_unit_id');
    }
}
