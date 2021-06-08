<?php

namespace App\Contracts\Embers\Objects\Links;

interface SharesLinks
{
    /**
     * Share a given Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id);
}
