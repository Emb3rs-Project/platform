<?php

namespace App\Contracts\Embers\Objects\Links;

use App\Models\Link;
use App\Models\User;

interface UpdatesLinks
{
    /**
     * Validate and update an existing Link.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @param  array  $input
     * @return \App\Models\Link
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(User $user, int $id, array $input): Link;
}
