<?php

namespace App\Events\Embers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class AddingTeamMember
{
    use Dispatchable;

    /**
     * The team instance.
     *
     * @var \App\Models\Team
     */
    public Team $team;

    /**
     * The email address of the added member.
     *
     * @var \App\Models\User
     */
    public User $email;

    /**
     * The teamRoleId of the added member.
     *
     * @var int
     */
    public int $teamRoleId;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Team  $team
     * @param  \App\Models\User  $user
     * @param  int  $teamRoleId
     * @return void
     */
    public function __construct(Team $team, User $user, int $teamRoleId)
    {
        $this->team = $team;
        $this->user = $user;
        $this->teamRoleId = $teamRoleId;
    }
}
