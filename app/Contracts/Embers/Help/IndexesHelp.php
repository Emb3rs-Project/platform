<?php

namespace App\Contracts\Embers\Help;

use App\Models\User;

interface IndexesHelp
{
    /**
     * Display all the available Faqs.
     *
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Collection<mixed, (\Illuminate\Database\Eloquent\Builder|\App\Models\FAQ)>|array)[]
     */
    public function index(User $user, array $input): array;
}
