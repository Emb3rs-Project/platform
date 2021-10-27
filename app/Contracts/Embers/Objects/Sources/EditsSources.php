<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\User;

interface EditsSources
{
    /**
     * Display the necessary objects for updating a given Source.
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
