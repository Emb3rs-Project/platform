<?php

namespace App\Contracts\Embers\Objects;

interface CreatesSinks
{
    /**
     * Validate and create a new sink.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create(mixed $user, array $input);

    /**
     * Redirect to a specific page after creating a new sink.
     * //TODO
     */
    public function redirectTo();
}
