<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\SharesLinks;
use App\EmbersPermissionable;
use App\Models\Link;

class ShareLink implements SharesLinks
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        $this->authorize($user);

        $link = Link::with(['geoSegments'])->findOrFail($id);

        // TODO: generate a sharing link

        $teamLinks = $user->currentTeam->links->pluck('id');

        $link->whereIn('id', $teamLinks)->get();

        return $link;
    }
}
