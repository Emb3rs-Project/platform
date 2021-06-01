<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\DestroysLinks;
use App\Models\Link;

class DestroyLink implements DestroysLinks
{
    /**
     * Find and delete an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return void
     */
    public function destroy($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'destroy-link'), 401);

        $link = Link::findOrFail($id);

        Link::destroy($link->id);
    }
}
