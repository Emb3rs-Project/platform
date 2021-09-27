<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface MarksAllNotificationsAsRead
{
    /**
     * Mark all the available notifications as read.
     */
    public function markAllAsRead(User $user): void;
}
