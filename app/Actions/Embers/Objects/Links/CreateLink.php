<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\EmbersPermissionable;

class CreateLink implements CreatesLinks
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Link.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user)
    {
        $this->authorize($user);

        return [];
    }
}
