<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\MySimulations;
use App\EmbersPermissionable;
use App\Models\Simulation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MySimulation implements MySimulations
{
    use EmbersPermissionable;

    /**
     * Display all the available Projects.
     */
    public function index(User $user)
    {
        $this->authorize($user);

        return Simulation::with([
            'project',
            'simulationType',
            'simulationType.unit',
            'simulationMetadata'
        ])->onInstitutionFor($user)
            ->orderBy('created_at','desc')
            ->paginate(5);

    }
}

