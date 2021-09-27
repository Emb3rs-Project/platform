<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\MarksNotificationsAsRead;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class MarkNotificationAsRead implements MarksNotificationsAsRead
{
    /**
     * Mark the notification as read.
     */
    public function markAsRead(User $user, string $id): DatabaseNotification
    {
        $notification = DatabaseNotification::find($id);

        $notification->markAsRead();

        return $notification;
    }
}
