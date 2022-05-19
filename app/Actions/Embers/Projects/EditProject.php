<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\EditsProjects;
use App\EmbersPermissionable;
use App\Models\Location;
use App\Models\Project;
use App\Models\User;

class EditProject implements EditsProjects
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Project.
     */
    public function edit(User $user, int $id): array
    {
        $this->authorize($user);

        $project = Project::with([
            //'location',
            'simulations',
            'simulations.simulationType',
            'simulations.simulationType.unit'
        ])->findOrFail($id);

        $locations = Location::all();

        return [
            $project,
            $locations,
        ];
    }
}
