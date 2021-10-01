<?php

namespace App\Contracts\Embers\Objects\Links;

use App\Models\User;
use Illuminate\Support\Collection;

interface ShowsLinks
{
    /**
     * Display the given Link.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(User $user, int $id): Collection;
}
