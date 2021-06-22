<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\MarksAllNotificationsAsRead;

class MarkAllNotificationsAsRead implements MarksAllNotificationsAsRead
{
    /**
     * Mark all the available notifications for the currently logged in user
     * as read.
     *
     * @param  mixed  $user
     * @return array
     */
    public function markAllAsRead($user)
    {
        $user->unreadNotifications->markAsRead();
    }
}
