<?php

namespace App\Models;

use Dotenv\Parser\Entry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimulationManagerSharingType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the user that owns the simulationManagerSharingType.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the simulationManager that owns the simulationManagerSharingType.
     */
    public function simulationManager(): BelongsTo
    {
        return $this->belongsTo(SimulationManager::class);
    }

    /**
     * Get the sharingType that owns the simulationManagerSharingType.
     */
    public function sharingType(): BelongsTo
    {
        return $this->belongsTo(SharingType::class);
    }

    /**
     * Get the entity that owns the simulationManagerSharingType.
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }
}
