<?php

namespace App\Contracts\Embers\Objects\Links;

interface EditsLinks
{
    /**
     * Display the necessary objects for updating a given Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function edit($user, int $id);
}
