<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

interface MarksNotificationsAsRead
{
    /**
     * Mark the notification as read.
     *
     * @param  \App\Models\User  $user
     * @param  string  $id
     * @return \Illuminate\Notifications\DatabaseNotification
     */
    public function markAsRead(User $user, string $id): DatabaseNotification;
}
