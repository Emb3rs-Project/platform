<?php

namespace App\Contracts\Embers\Objects\Links;

interface StoresLinks
{
    /**
     * Validate and create a new Link.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store($user, array $input);
}
