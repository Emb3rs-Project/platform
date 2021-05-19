<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;

class ShowLink implements ShowsLinks
{
    public function show(mixed $user, int $id)
    {
        $link = Link::with(['geoSegments'])->findOrFail($id);

        Gate::authorize('view', $link);

        $teamLinks = $user->currentTeam->links->pluck('id');

        $link->whereIn('id', $teamLinks)->get();

        return $link;
    }
}
