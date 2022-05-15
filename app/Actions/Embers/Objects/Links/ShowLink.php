<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\EmbersPermissionable;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Collection;

class ShowLink implements ShowsLinks
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(User $user, int $id): Link
    {
        $this->authorize($user);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $link = Link::query()
            ->with(['geoSegments'])
            ->whereIn('id', $teamLinks)
            ->findOrFail($id);
            //->get();

        return $link;
    }
}
