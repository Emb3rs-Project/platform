<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\Models\Instance;

class DestroySource implements DestroysSources
{
    /**
     * Find and delete an existing Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'destroy-source'), 401);

        $source = Instance::findOrFail($id);

        Instance::destroy($source->id);
    }
}
