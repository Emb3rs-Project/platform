<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;

class IndexLink implements IndexesLinks
{
    /**
     * Display all the available Sinks.
     *
     * @param mixed  $user
     * @return mixed
     */
    public function index(mixed $user)
    {
        Gate::authorize('viewAny', Link::class);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $links = Link::with(['geoSegments'])->whereIn('id', $teamLinks)->get();

        return $links;
    }
}
