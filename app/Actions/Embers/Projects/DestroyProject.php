<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\DestroysProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\User;

class DestroyProject implements DestroysProjects
{
    use EmbersPermissionable;

    /**
     * Find and delete an existing Project.
     */
    public function destroy(User $user, int $id): void
    {
        $this->authorize($user);

        $project = Project::query()->findOrFail($id);

        // Project::destroy($project->id);
        $project->delete();
    }
}
