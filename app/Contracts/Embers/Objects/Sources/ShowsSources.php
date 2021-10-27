<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\User;

interface ShowsSources
{
    /**
     * Display the given Source.
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
