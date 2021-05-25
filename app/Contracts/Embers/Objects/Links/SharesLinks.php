<?php

namespace App\Contracts\Embers\Objects\Links;

interface SharesLinks
{
    /**
     * Share a given Link.
     *
     * @param  int  $id
     * @return mixed
     */
    public function share(int $id);
}
