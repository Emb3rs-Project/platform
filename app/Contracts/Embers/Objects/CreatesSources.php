<?php

namespace App\Contracts\Embers\Objects;

interface CreatesSources
{
    /**
     * Validate and create a new source.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input);

    /**
     * Redirect to a specific page after creating a new source
     * //TODO
     */
    public function redirectTo();
}
