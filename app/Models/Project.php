<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'location_id'
    ];

    // Table locations
    public function locations() : HasMany
    {
        return $this->hasMany(Location::class, 'project_id');
    }

    //Table projects
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    // Table simulations
    public function simulations() : HasMany
    {
        return $this->hasMany(Simulation::class, 'project_id');
    }

    // Table team_project
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            Team::class,
            'team_project',
            'project_id',
            'team_id'
        );
    }
}
