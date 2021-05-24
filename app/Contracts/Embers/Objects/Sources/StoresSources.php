<?php

namespace App\Contracts\Embers\Objects\Sources;

interface StoresSources
{
    /**
     * Validate and create a new Source.
     *
     * @param  array  $input
     * @return mixed
     */
    public function store(array $input);
}
