<?php

namespace App\Contracts\Embers\Objects\Links;

interface ShowsLinks
{
    /**
     * Display the given Link.
     *
     * @param  mixed  $user
     * @param  int    $id
     */
    public function show(mixed $user, int $id);
}
