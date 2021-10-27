<?php

namespace App\Contracts\Embers\News;

use App\Models\News;
use App\Models\User;

interface ShowsNews
{
    /**
     * Display all the available Projects.
     *
     * @param \App\Models\User
     * @param  int  $newsId
     * @return \App\Models\News
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(User $user, int $newsId): News;
}
