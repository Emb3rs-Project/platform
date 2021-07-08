<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\EmbersPermissionable;
use App\Models\Link;

class IndexLink implements IndexesLinks
{
    use EmbersPermissionable;

    /**
     * Display all the available Links.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user)
    {
        $this->authorize($user);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $links = Link::with(['geoSegments'])->whereIn('id', $teamLinks)->get();

        return $links;
    }
}
