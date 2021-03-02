<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'observation',
    ];

    /**
     * Get the user that owns the userLog.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the userRegistrationStatus that owns the userLog.
     */
    public function userRegistrationStatus(): BelongsTo
    {
        return $this->belongsTo(UserRegistrationStatus::class);
    }
}
