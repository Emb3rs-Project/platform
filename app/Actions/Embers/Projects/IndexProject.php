<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\IndexesProjects;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class IndexProject implements IndexesProjects
{
    /**
     * Display all the available Projects.
     *
     * @param mixed  $user
     * @return [Project]
     */
    public function index(mixed $user)
    {
        Gate::authorize('viewAny', Project::class);

        $teamProjects = $user->currentTeam->projects->pluck('id');

        $projects = Project::with(['location'])
            ->whereIn('id', $teamProjects)
            ->get();

        return $projects;
    }
}
