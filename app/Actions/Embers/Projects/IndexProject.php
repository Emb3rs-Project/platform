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

        $output = $projects->map(function ($item) {
            if (isset($item->location)) {
                $item['data'] = $item->location->geoObject;
            }

            return $item;
        });

        return $output;
    }
}
