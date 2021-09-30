<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\User;

interface CreatesSources
{
    /**
     * Display the necessary objects for the creation of a Source.
     *
     * @param  \App\Models\User  $user
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function create(User $user): array;
}
