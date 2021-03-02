<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRegistrationRequest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the user that owns the userRegistrationRequest.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the userRegistrationStatus that owns the userRegistrationRequest.
     */
    public function userRegistrationStatus(): BelongsTo
    {
        return $this->belongsTo(UserRegistrationStatus::class);
    }

    /**
     * Get the country that owns the userRegistrationRequest.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the region that owns the userRegistrationRequest.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
