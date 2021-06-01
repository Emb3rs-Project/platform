<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\Models\Instance;

class ShowSink implements ShowsSinks
{
    /**
     * Find and return an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function show($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-sink'), 401);

        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        return $sink;
    }
}
