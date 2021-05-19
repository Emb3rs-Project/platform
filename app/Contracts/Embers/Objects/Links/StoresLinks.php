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
    public function store(mixed $user, array $input);

    /**
     * Redirect to a specific page after creating a new sink.
     * //TODO
     */
    public function redirectTo();
}
