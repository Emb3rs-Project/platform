<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\SharesLinks;
use App\EmbersPermissionable;
use App\Models\Link;
use App\Models\User;

class ShareLink implements SharesLinks
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Link.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function share(User $user, int $id)
    {
        $this->authorize($user);

        $link = Link::query()->with(['geoSegments'])->findOrFail($id);

        // TODO: generate a sharing link

        $teamLinks = $user->currentTeam->links->pluck('id');

        $link->whereIn('id', $teamLinks)->get();

        return $link;
    }
}
