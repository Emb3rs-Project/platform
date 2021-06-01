<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\DestroysProjects;
use App\Models\Project;

class DestroyProject implements DestroysProjects
{
    /**
     * Find and delete an existing Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return void
     */
    public function destroy($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'destroy-project'), 401);

        $project = Project::findOrFail($id);

        Project::destroy($project->id);
    }
}
