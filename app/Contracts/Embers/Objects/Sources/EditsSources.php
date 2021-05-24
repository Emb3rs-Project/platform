<?php

namespace App\Contracts\Embers\Objects\Sources;

interface EditsSources
{
    /**
     * Display the necessary objects for updating a given Source.
     *
     * @param  int  $id
     * @return mixed
     */
    public function edit(int $id);
}
