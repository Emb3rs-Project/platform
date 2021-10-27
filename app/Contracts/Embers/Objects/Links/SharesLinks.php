<?php

namespace App\Contracts\Embers\Objects\Links;

use App\Models\User;

interface SharesLinks
{
    /**
     * Share a given Link.
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
