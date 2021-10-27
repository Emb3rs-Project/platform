<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\EmbersPermissionable;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Collection;

class IndexLink implements IndexesLinks
{
    use EmbersPermissionable;

    /**
     * Display all the available Links.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Support\Collection
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function index(User $user): Collection
    {
        $this->authorize($user);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $links = Link::query()->with(['geoSegments'])->whereIn('id', $teamLinks)->get();

        return $links;
    }
}
