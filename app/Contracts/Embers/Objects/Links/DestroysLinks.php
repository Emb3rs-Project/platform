<?php

namespace App\Contracts\Embers\Objects\Links;

use App\Models\Link;
use App\Models\User;

interface DestroysLinks
{
    /**
     * Delete an existing Link.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(User $user, int $id): Link;
}
