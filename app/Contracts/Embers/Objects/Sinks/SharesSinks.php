<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\User;

interface SharesSinks
{
    /**
     * Share a given Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function share(User $user, int $id);
}
