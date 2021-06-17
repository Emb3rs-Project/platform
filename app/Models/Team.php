<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    // Table team_instance
    public function instances(): BelongsToMany
    {
        return $this->belongsToMany(
            Instance::class,
            'team_instance',
            'team_id',
            'instance_id'
        );
    }

    // Table team_link
    public function links(): BelongsToMany
    {
        return $this->belongsToMany(
            Link::class,
            'team_link',
            'team_id',
            'link_id'
        );
    }

    // Table team_project
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'team_project',
            'team_id',
            'project_id'
        );
    }

    // Table team_location
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(
            Location::class,
            'team_location',
            'team_id',
            'location_id'
        );
    }

    // Table team_roles
    public function teamRoles(): HasMany
    {
        return $this->hasMany(TeamRole::class, 'team_id');
    }

    /**
     * Get all of the users that belong to the team.
     *
     * Note: This function is overriding the users() function from
     *       JetstreamTeam class, so it can be adapted to EMB3Rs use case.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            Jetstream::userModel(),
            Jetstream::membershipModel(),
            'team_id',
            'user_id'
        )->withPivot('team_role_id')->withTimestamps()->as('membership');
    }
}
