<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\IndexesProjects;
use App\EmbersPermissionable;
use App\Models\Project;

class IndexProject implements IndexesProjects
{
    use EmbersPermissionable;

    /**
     * Display all the available Projects.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user)
    {
        $this->authorize($user);

        $teamProjects = $user->currentTeam->projects->pluck('id');

        $projects = Project::with(['location'])->whereIn('id', $teamProjects)->get();

        return $projects;
    }
}
