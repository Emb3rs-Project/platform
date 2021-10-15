<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\CreatesProjects;
use App\EmbersPermissionable;
use App\Models\Location;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CreateProject implements CreatesProjects
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $user
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Location>[]
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function create(User $user): Collection
    {
        $this->authorize($user);

        $teamLocations = $user->currentTeam->instances->pluck('location_id');

        $locations = Location::query()->whereIn('id', $teamLocations->toArray())->get();

        return $locations;
    }
}
