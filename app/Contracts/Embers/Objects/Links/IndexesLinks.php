<?php

namespace App\Contracts\Embers\Objects\Links;

use App\Models\User;
use Illuminate\Support\Collection;

interface IndexesLinks
{
    /**
     * Display all the available Links.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Support\Collection
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function index(User $user): Collection;
}
