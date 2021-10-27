<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\EmbersPermissionable;
use App\Models\Link;
use App\Models\Location;
use App\Models\User;

class EditLink implements EditsLinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Link.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit(User $user, int $id): array
    {
        $this->authorize($user);

        $link = Link::query()->with(['geoSegments'])->findOrFail($id);

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
