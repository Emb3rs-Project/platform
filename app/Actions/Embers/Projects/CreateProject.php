<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\CreatesProjects;
use App\EmbersPermissionable;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CreateProject implements CreatesProjects
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Project.
     */
    public function create(User $user): Collection
    {
        $this->authorize($user);

        $locations = Location::all();

        return $locations;
    }
}
