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
     * @return \Illuminate\Database\Eloquent\Collection[]
     */
    public function query(User $user, string $query): array;
}
