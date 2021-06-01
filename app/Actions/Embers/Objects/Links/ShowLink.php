<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\Models\Link;

class ShowLink implements ShowsLinks
{
    /**
     * Find and return an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return Instance
     */
    public function show($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-link'), 401);

        $link = Link::with(['geoSegments'])->findOrFail($id);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $link->whereIn('id', $teamLinks)->get();

        return $link;
    }
}
