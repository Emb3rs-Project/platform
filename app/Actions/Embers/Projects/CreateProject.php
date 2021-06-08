<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\CreatesProjects;
use App\EmbersPermissionable;
use App\Models\Location;

class CreateProject implements CreatesProjects
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Project.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user)
    {
        $this->authorize($user);

        $locations = Location::all();

        return $locations;
    }
}
