<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\User;

interface CreatesSinks
{
    /**
     * Display the necessary objects for the creation of a Sink.
     *
     * @param  \App\Models\User  $user
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function create(User $user): array;
}
