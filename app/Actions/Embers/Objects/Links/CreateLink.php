<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\CreatesLinks;

class CreateLink implements CreatesLinks
{
    /**
     * Display the necessary objects for the creation of a Link.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'create-link'), 401);

        return [];
    }
}
