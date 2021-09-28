<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface RemovesAllNotifications
{
    /**
     * Remove all the available notifications.
     */
    public function remove(User $user): void;
}
