<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'path',
        'description',
        'version'
    ];

    /**
     * Get the permissionProfiles for the permission.
     */
    public function permissionProfiles(): HasMany
    {
        return $this->hasMany(PermissionProfile::class);
    }
}
