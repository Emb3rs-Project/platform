<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ShowLink implements ShowsLinks
{
    /**
     * Find and return an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return Instance
     */
    public function show($user, int $id)
    {
        $link = Link::with(['geoSegments'])->findOrFail($id);

        Gate::authorize('view', $link);

        $teamLinks = Auth::user()->currentTeam->links->pluck('id');

        $link->whereIn('id', $teamLinks)->get();

        return $link;
    }
}
