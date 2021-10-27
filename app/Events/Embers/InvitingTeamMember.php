<?php

namespace App\Events\Embers;

use App\Models\Team;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitingTeamMember
{
    use Dispatchable, SerializesModels;

    /**
     * The team instance.
     *
     * @var Team
     */
    public Team $team;

    /**
     * The email address of the invitee.
     *
     * @var string
     */
    public string $email;

    /**
     * The teamRoleId of the invitee.
     *
     * @var int
     */
    public int $teamRoleId;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Team  $team
     * @param  string  $email
     * @param  int  $teamRoleId
     * @return void
     */
    public function __construct(Team $team, string $email, int $teamRoleId)
    {
        $this->team = $team;
        $this->email = $email;
        $this->teamRoleId = $teamRoleId;
    }
}
