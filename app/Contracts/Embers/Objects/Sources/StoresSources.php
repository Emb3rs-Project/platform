<?php

namespace App\Contracts\Embers\Objects\Sources;

interface StoresSources
{
    /**
     * Validate and create a new Source.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store($user, array $input);

    /**
     * Redirect to a specific page after creating a new source
     * //TODO
     */
    public function redirectTo();
}
