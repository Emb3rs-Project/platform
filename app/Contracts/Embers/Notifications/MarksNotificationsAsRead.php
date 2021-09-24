<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

interface MarksNotificationsAsRead
{
    /**
     * Mark the notification as read.
     */
    public function markAsRead(User $user, string $id): DatabaseNotification;
}
