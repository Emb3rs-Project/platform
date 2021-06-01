<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\Models\Instance;

class DestroySink implements DestroysSinks
{
    /**
     * Find and delete an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return void
     */
    public function destroy($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'destroy-sink'), 401);

        $sink = Instance::findOrFail($id);

        Instance::destroy($sink->id);
    }
}
