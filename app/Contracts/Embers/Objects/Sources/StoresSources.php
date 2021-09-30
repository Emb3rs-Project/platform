<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\Instance;
use App\Models\User;

interface StoresSources
{
    /**
     * Validate and create a new Source.
     *
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return \App\Models\Instance
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(User $user, array $input): Instance;
}
