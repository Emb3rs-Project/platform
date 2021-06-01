<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Jetstream\Jetstream;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * The accessors to append to the model's array form.
    *
    * @var array
    */
    protected $appends = [
        'profile_photo_url',
    ];

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

        return TeamRole::where('id', $team->users->where('id', $this->id)->first()->membership->team_role_id)->first();
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

        foreach ($permissions['permissions'] as &$permission) {
            $permission = $permission['name'];
        }
        unset($permission);

        return Arr::flatten($permissions);
    }
}
