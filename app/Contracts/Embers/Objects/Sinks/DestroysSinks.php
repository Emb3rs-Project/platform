<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\User;

interface DestroysSinks
{
    /**
     * Delete an existing Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(User $user, int $id): void;
}
