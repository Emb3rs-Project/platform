<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface IndexesNotifications
{
    /**
     * Display all the available notifications.
     */
    public function index(User $user): array;
}
