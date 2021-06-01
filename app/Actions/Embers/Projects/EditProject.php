<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\EditsProjects;
use App\Models\Location;
use App\Models\Project;

class EditProject implements EditsProjects
{
    /**
     * Display the necessary objects for updating a given Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return mixed
     */
    public function edit($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'edit-project'), 401);

        $project = Project::with(['location'])->findOrFail($id);

        $locations = Location::all();

        return [
            $project,
            $locations,
        ];
    }
}
