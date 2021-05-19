<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\Models\GeoSegment;
use App\Models\Link;
use App\Models\Location;
use Illuminate\Support\Facades\Gate;

class EditLink implements EditsLinks
{
    /**
     * Display the necessary objects for updating a given Link.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @param  array  $input
     * @return mixed
     */
    public function edit(mixed $user, int $id)
    {
        $link = Link::with(['geoSegments'])->findOrFail($id);

        Gate::authorize('view', $link);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $links = Link::with(['geoSegments'])->whereIn('id', $teamLinks)->get();

        $locations = Location::with(['geoObject'])->get();

        return [
            $links,
            $locations,
            $link
        ];
    }
}
