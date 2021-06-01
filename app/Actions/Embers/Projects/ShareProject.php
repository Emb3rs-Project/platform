<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\SharesProjects;
use App\Models\Project;

class ShareProject implements SharesProjects
{
    /**
     * Find and return an existing Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'share-project'), 401);

        $project = Project::with(['location'])->findOrFail($id);

        // TODO: generate a sharing link

        return $project;
    }
}
