<?php

namespace App\Contracts\Embers\Objects\Links;

interface StoresLinks
{
    // TODO: fix it when links are ready
    /**
     * Validate and create a new Link.
     *
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return mixed
     */
    public function store($user, array $input);
}
