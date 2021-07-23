<?php

namespace App\Contracts\Embers\Notifications;

interface MarksAllNotificationsAsRead
{
    /**
     * Mark all the available notifications for the currently logged in user
     * as read.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function markAllAsRead($user);
}
