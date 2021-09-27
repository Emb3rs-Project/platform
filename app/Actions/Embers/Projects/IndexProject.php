<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\IndexesProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class IndexProject implements IndexesProjects
{
    use EmbersPermissionable;

    /**
     * Display all the available Projects.
     */
    public function index(User $user): Collection
    {
        $this->authorize($user);

        $teamProjects = $user->currentTeam->projects->pluck('id');

        $projects = Project::with([
            'location',
            'simulations',
            'simulations.simulationType',
            'simulations.simulationType.unit'
        ])->whereIn('id', $teamProjects)->get();

        return $projects;
    }
}
