<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\IndexesProjects;
use App\Models\Project;

class IndexProject implements IndexesProjects
{
    /**
     * Display all the available Projects.
     *
     * @param  mixed  $user
     * @return [Project]
     */
    public function index($user)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'index-project'), 401);

        $teamProjects = $user->currentTeam->projects->pluck('id');

        $projects = Project::with(['location'])->whereIn('id', $teamProjects)->get();

        return $projects;
    }
}
