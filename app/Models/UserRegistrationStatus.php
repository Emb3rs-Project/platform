<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRegistrationStatus extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'description',
    ];

    /**
     * Get the userLogs for the userRegistrationStatus.
     */
    public function userLogs(): HasMany
    {
        return $this->hasMany(UserLog::class);
    }

    /**
     * Get the userRegistrationRequests for the userRegistrationStatus.
     */
    public function userRegistrationRequests(): HasMany
    {
        return $this->hasMany(UserRegistrationRequest::class);
    }
}
