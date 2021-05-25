<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\SharesProjects;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Gate;

class ShareProject implements SharesProjects
{
    /**
     * Find and return an existing Project.
     *
     * @param  int  $id
     * @return mixed
     */
    public function share(int $id)
    {
        $project = Project::with(['location'])->findOrFail($id);

        Gate::authorize('view', $project);
        // TODO: also check for sharing permissions

        // TODO: generate a sharing link

        return $project;
    }
}
