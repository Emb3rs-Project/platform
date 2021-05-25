<?php

namespace App\Contracts\Embers\Objects\Links;

interface StoresLinks
{
    /**
     * Validate and create a new Link.
     *
     * @param  array  $input
     * @return mixed
     */
    public function store(array $input);
}
