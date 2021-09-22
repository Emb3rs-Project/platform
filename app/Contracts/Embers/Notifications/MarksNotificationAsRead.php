<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface MarksNotificationAsRead
{
    /**
     * Mark the notification for the currently logged in user as read.
     *
     * @param  App\Models\User  $user
     * @param  int  $id
     * @return mixed
     */
    public function markAsRead(User $user, int $id);
}
