<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\EmbersPermissionable;
use App\Models\Link;

class ShowLink implements ShowsLinks
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function show($user, int $id)
    {
        $this->authorize($user);

        $link = Link::with(['geoSegments'])->findOrFail($id);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $link->whereIn('id', $teamLinks)->get();

        return $link;
    }
}
