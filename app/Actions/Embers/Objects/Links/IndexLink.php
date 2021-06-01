<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Models\Link;

class IndexLink implements IndexesLinks
{
    /**
     * Display all the available Links.
     *
     * @param  mixed  $user
     * @return [Instance]
     */
    public function index($user)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'index-link'), 401);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $links = Link::with(['geoSegments'])->whereIn('id', $teamLinks)->get();

        return $links;
    }
}
