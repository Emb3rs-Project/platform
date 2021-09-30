<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\User;

interface ShowsSinks
{
    /**
     * Display the given Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(User $user, int $id): array;
}
