<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\EmbersPermissionable;
use App\Models\Link;
use App\Models\Location;

class EditLink implements EditsLinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return mixed
     */
    public function edit($user, int $id)
    {
        $this->authorize($user);

        $link = Link::with(['geoSegments'])->findOrFail($id);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $links = Link::with(['geoSegments'])->whereIn('id', $teamLinks)->get();

        $locations = Location::all();

        return [
            $links,
            $locations,
            $link
        ];
    }
}
