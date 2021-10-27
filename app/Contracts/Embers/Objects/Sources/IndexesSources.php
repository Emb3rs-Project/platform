<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\Instance;
use App\Models\User;

interface IndexesSources
{
    /**
     * Display all existing sources.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function index(User $user);
}
