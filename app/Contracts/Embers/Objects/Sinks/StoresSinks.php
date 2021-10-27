<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\Instance;
use App\Models\User;

interface StoresSinks
{
    /**
     * Validate and create a new Sink.
     *
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return \App\Models\Instance
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function store(User $user, array $input): Instance;
}
