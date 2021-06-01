<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\SharesSources;
use App\Models\Instance;

class ShareSource implements SharesSources
{
    /**
     * Find and return an existing Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'share-source'), 401);

        $source = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        // TODO: generate a sharing link

        return $source;
    }
}
