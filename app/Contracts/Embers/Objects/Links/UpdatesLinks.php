<?php

namespace App\Contracts\Embers\Objects\Links;

interface UpdatesLinks
{
    /**
     * Validate and update an existing Link.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @param  array  $input
     * @return mixed
     */
    public function update($user, int $id, array $input);
}
