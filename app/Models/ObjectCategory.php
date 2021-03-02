<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectCategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the objectType that owns the objectCategory.
     */
    public function objectType(): BelongsTo
    {
        return $this->belongsTo(ObjectType::class);
    }

    /**
     * Get the objectTemplate for the objectCategory.
     */
    public function objectTemplates(): HasMany
    {
        return $this->hasMany(ObjectTemplate::class);
    }
}
