<?php

namespace App\Contracts\Embers\Search;

use App\Models\User;

interface QueriesSearch
{
    /**
     * Display the necessary objects for the creation of a Simulation.
     *
     * @param  \App\Models\User  $user
     * @param  string  $query
     * @return array
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function query(User $user, string $query): array;
}
