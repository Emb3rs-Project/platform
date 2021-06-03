<?php

namespace App;

use App\Models\TeamRole;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Laravel\Jetstream\Jetstream;

trait HasEmbersTeams
{
    /**
     * Get all of the teams the user belongs to.
     *
     * Note: This function is overriding the teams() function from
     *       HasTeams trait, so it can be adapted to EMB3Rs use case.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(
            Jetstream::teamModel(),
            Jetstream::membershipModel(),
            'user_id',
            'team_id'
        )->withPivot('team_role_id')->withTimestamps()->as('membership');
    }

    /**
     * Get the role that the user has on the team.
     *
     * Note: This function is overriding the teamRole() function from
     *       HasTeams trait, so it can be adapted to EMB3Rs use case.
     *
     * @param  mixed  $team
     * @return mixed
     */
    public function teamRole($team)
    {
        if ($this->ownsTeam($team)) {
            return 'Owner';
        }

        if (! $this->belongsToTeam($team)) {
            return;
        }

        return TeamRole::where('id', $team->users->where('id', $this->id)->first()->membership->team_role_id)
            ->first();
    }

    /**
     * Determine if the user has the given role on the given team.
     *
     * Note: This function is overriding the hasTeamRole() function from
     *       HasTeams trait, so it can be adapted to EMB3Rs use case.
     *
     * @param  mixed  $team
     * @param  string  $role
     * @return bool
     */
    public function hasTeamRole($team, string $role)
    {
        if ($this->ownsTeam($team)) {
            return true;
        }

        return $this->belongsToTeam($team) &&
            TeamRole::where('id', $team->users->where('id', $this->id)->first()->membership->team_role_id)
                ->first(['role']) === $role;
    }

    /**
    * Get the user's permissions for the given team.
    *
    * Note: This function is overriding the teamPermissions() function from
    *       HasTeams trait, so it can be adapted to EMB3Rs use case.
    *
    * @param  mixed  $team
    * @return array
    */
    public function teamPermissions($team)
    {
        if ($this->ownsTeam($team)) {
            return ['*'];
        }

        if (! $this->belongsToTeam($team)) {
            return [];
        }

        $permissions = $this->teamRole($team)->permissions;

        // TODO: not sure if we are going to need this ATM.
        // foreach ($permissions['permissions'] as &$permission) {
        //     $permission = $permission['name'];
        // }
        // unset($permission);

        return Arr::flatten($permissions);
    }
}
