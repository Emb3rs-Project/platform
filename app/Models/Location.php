<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use  SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'decription',
        'type',
        'data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    // Table locations
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // Table projects
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'location_id');
    }

    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class, 'location_id');
    }

    // Table team_location
    public function team(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'team_location',
            'location_id',
            'team_id'
        );
    }
}
