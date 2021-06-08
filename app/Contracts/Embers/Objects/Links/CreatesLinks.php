<?php

namespace App\Contracts\Embers\Objects\Links;

interface CreatesLinks
{
    /**
     * Display the necessary objects for the creation of a Link.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user);
}
