<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\User;

interface DestroysSources
{
    /**
     * Delete an existing Source.
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
