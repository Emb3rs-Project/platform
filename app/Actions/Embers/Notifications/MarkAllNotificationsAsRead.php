<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\MarksAllNotificationsAsRead;
use App\Models\User;

class MarkAllNotificationsAsRead implements MarksAllNotificationsAsRead
{
    /**
     * Mark all the available notifications for the currently logged in user
     * as read.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }
}
