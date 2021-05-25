<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\EditsProjects;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class EditProject implements EditsProjects
{
    /**
     * Display the necessary objects for updating a given Project.
     *
     * @param  int  $id
     * @param  array  $input
     * @return mixed
     */
    public function edit(int $id)
    {
        $project = Project::with(['location'])->findOrFail($id);

        Gate::authorize('view', $project);

        $locations = Location::all();

        return [
            $project,
            $locations,
        ];
    }
}
