<?php

namespace App\Contracts\Embers\Objects\Links;

interface ShowsLinks
{
    /**
     * Display the given Link.
     *
     * @param  mixed  $user
     * @param  int    $source
     */
    public function show(mixed $user, int $id);
}
