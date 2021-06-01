<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\CreatesProjects;
use App\Models\Location;

class CreateProject implements CreatesProjects
{
    /**
     * Display the necessary objects for the creation of a Project.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'create-project'), 401);

        $locations = Location::all();

        return $locations;
    }
}
