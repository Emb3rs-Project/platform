<?php

namespace App\Contracts\Embers\News;

use App\Models\User;
use Illuminate\Support\Collection;

interface IndexesNews
{
    /**
     * Display all the available news.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Support\Collection
     */
    public function index(User $user): Collection;
}
