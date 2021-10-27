<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface MarksAllNotificationsAsRead
{
    /**
     * Mark all the available notifications as read.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function markAllAsRead(User $user): void;
}
