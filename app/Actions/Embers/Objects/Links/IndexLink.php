<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;

class IndexLink implements IndexesLinks
{
    /**
     * Display all the available Links.
     *
     * @param mixed  $user
     * @return [Instance]
     */
    public function index(mixed $user)
    {
        Gate::authorize('viewAny', Link::class);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $links = Link::with(['geoSegments'])->whereIn('id', $teamLinks)->get();

        return $links;
    }
}
