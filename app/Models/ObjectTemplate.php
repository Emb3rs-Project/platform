<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectTemplate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the objectCategory that owns the objectTemplate.
     */
    public function objectCategory(): BelongsTo
    {
        return $this->belongsTo(ObjectCategory::class);
    }

    /**
     * Get the objectInstances for the user.
     */
    public function objectInstances(): HasMany
    {
        return $this->hasMany(ObjectInstance::class);
    }
}
