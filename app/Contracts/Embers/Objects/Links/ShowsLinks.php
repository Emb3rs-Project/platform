<?php

namespace App\Contracts\Embers\Objects\Links;

interface ShowsLinks
{
    /**
     * Display the given Link.
     *
     * @param  int  $id
     */
    public function show(int $id);
}
