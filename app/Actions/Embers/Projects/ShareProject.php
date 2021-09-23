<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\SharesProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\User;

class ShareProject implements SharesProjects
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share(User $user, int $id): Project
    {
        $this->authorize($user);

        $project = Project::with(['location'])->findOrFail($id);

        // TODO: generate a sharing link

        return $project;
    }
}
