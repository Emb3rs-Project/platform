<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObjectInstanceSharingType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the user that owns the objectInstanceSharingType.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the entity that owns the objectInstanceSharingType.
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Get the sharingType that owns the objectInstanceSharingType.
     */
    public function sharingType(): BelongsTo
    {
        return $this->belongsTo(SharingType::class);
    }

    /**
     * Get the objectInstance that owns the objectInstanceSharingType.
     */
    public function objectInstance(): BelongsTo
    {
        return $this->belongsTo(ObjectInstance::class);
    }
}
