<?php

namespace App\Contracts\Embers\Objects\Links;

use App\Models\User;

interface CreatesLinks
{
    /**
     * Display the necessary objects for the creation of a Link.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function create(User $user): array;
}
