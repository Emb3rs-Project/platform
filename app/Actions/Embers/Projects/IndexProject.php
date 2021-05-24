<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\IndexesProjects;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IndexProject implements IndexesProjects
{
    /**
     * Display all the available Projects.
     *
     * @return [Project]
     */
    public function index()
    {
        Gate::authorize('viewAny', Project::class);

        $teamProjects = Auth::user()->currentTeam->projects->pluck('id');

        $projects = Project::with(['location'])->whereIn('id', $teamProjects)->get();

        return $projects;
    }
}
