<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\User;

interface SharesSources
{
    /**
     * Share a given Source.
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
