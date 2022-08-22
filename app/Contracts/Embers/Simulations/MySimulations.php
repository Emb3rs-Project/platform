<?php

namespace App\Contracts\Embers\Simulations;


use App\Models\User;

interface MySimulations
{
    /**
     * Display all available Simulations.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @return mixed
     */
    public function index(User $user);
}
