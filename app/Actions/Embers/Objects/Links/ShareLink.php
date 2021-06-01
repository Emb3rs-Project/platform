<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\SharesLinks;
use App\Models\Link;

class ShareLink implements SharesLinks
{
    /**
     * Find and return an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'share-link'), 401);

        $link = Link::with(['geoSegments'])->findOrFail($id);

        // TODO: generate a sharing link

        $teamLinks = $user->currentTeam->links->pluck('id');

        $link->whereIn('id', $teamLinks)->get();

        return $link;
    }
}
