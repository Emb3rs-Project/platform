<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\User;

interface EditsSinks
{
    /**
     * Display the necessary objects for updating a given Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function edit(User $user, int $id): array;
}
