<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the userProfiles for the profile.
     */
    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }

    /**
     * Get the userEntities for the profile.
     */
    public function userEntities(): HasMany
    {
        return $this->hasMany(UserEntity::class);
    }

    /**
     * Get the permissionProfiles for the profile.
     */
    public function permissionProfiles(): HasMany
    {
        return $this->hasMany(PermissionProfile::class);
    }
}
