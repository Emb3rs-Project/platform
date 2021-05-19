<?php

namespace App\Contracts\Embers\Objects\Links;

interface IndexesLinks
{
    /**
     * Display all available Links.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index(mixed $user);
}
