<?php

namespace App\Contracts\Embers\Objects\Links;

interface IndexesLinks
{
    /**
     * Display all available Sinks.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index(mixed $user);

    public function redirectTo();
}
