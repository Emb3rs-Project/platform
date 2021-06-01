<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\CreatesProjects;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class CreateProject implements CreatesProjects
{
    /**
     * Display the necessary objects for the creation of a Project.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user)
    {
        Gate::authorize('create', Project::class);

        $locations = Location::all();

        return $locations;
    }
}
