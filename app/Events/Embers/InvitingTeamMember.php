<?php

namespace App\Events\Embers;

use Illuminate\Foundation\Events\Dispatchable;

class InvitingTeamMember
{
    use Dispatchable;

    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * The email address of the invitee.
     *
     * @var mixed
     */
    public $email;

    /**
     * The teamRoleId of the invitee.
     *
     * @var mixed
     */
    public $teamRoleId;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $team
     * @param  mixed  $email
     * @param  int  $teamRoleId
     * @return void
     */
    public function __construct($team, $email, $teamRoleId)
    {
        $this->team = $team;
        $this->email = $email;
        $this->teamRoleId = $teamRoleId;
    }
}
