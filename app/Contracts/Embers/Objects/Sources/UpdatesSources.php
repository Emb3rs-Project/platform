<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\Instance;
use App\Models\User;

interface UpdatesSources
{
    /**
     * Validate and update an existing Source.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @param  array  $input
     * @return \App\Models\Instance
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user, int $id, array $input): Instance;
}
