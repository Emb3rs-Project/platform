<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface IndexesNotifications
{
    /**
     * Display all the available notifications.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function index(User $user): array;
}
