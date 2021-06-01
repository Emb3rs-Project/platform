<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\SharesLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ShareLink implements SharesLinks
{
    /**
     * Find and return an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        $link = Link::with(['geoSegments'])->findOrFail($id);

        Gate::authorize('view', $link);
        // TODO: also check for sharing permissions

        // TODO: generate a sharing link

        $teamLinks = Auth::user()->currentTeam->links->pluck('id');

        $link->whereIn('id', $teamLinks)->get();

        return $link;
    }
}
