<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\MarksNotificationAsRead;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class MarkNotificationAsRead implements MarksNotificationAsRead
{
    /**
     * Mark the notification as read.
     */
    public function markAsRead(User $user, int $id): DatabaseNotification
    {
        $notification = $user->notifications->firstOrFail($id);

        $notification->markAsRead();

        return $notification;
    }
}
