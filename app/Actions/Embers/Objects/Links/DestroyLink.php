<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\DestroysLinks;
use App\EmbersPermissionable;
use App\Models\Link;

class DestroyLink implements DestroysLinks
{
    use EmbersPermissionable;

    /**
     * Find and delete an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id)
    {
        $this->authorize($user);

        $link = Link::findOrFail($id);

        Link::destroy($link->id);
    }
}
