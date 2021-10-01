<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\User;

interface IndexesSinks
{
    /**
     * Display all available Sinks.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function index(User $user);
}
