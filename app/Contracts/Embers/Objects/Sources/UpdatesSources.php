<?php

namespace App\Contracts\Embers\Objects\Sources;

interface UpdatesSources
{
    /**
     * Validate and update an existing Source.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @param  array  $input
     * @return mixed
     */
    public function update($user, int $id, array $input);
}
