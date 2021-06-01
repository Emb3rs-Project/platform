<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\SharesSinks;
use App\Models\Instance;

class ShareSink implements SharesSinks
{
    /**
     * Find and return an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'share-sink'), 401);

        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        // TODO: generate a sharing link

        return $sink;
    }
}
