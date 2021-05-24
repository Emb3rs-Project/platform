<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\DestroysProjects;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class DestroyProject implements DestroysProjects
{
    /**
     * Find and delete an existing Project.
     *
     * @param  int  $id
     * @param  array  $input
     * @return void
     */
    public function destroy(int $id)
    {
        $project = Project::findOrFail($id);

        Gate::authorize('delete', $project);

        Project::destroy($project->id);
    }
}
