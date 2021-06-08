<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\DestroysProjects;
use App\EmbersPermissionable;
use App\Models\Project;

class DestroyProject implements DestroysProjects
{
    use EmbersPermissionable;

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
        $this->authorize($user);

        $project = Project::findOrFail($id);

        Project::destroy($project->id);
    }
}
