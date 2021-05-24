<?php

namespace App\Contracts\Embers\Objects\Sources;

interface UpdatesSources
{
    /**
     * Validate and update an existing Source.
     *
     * @param  int  $id
     * @param  array  $input
     * @return mixed
     */
    public function update(int $id, array $input);
}
