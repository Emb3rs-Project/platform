<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\MarksNotificationsAsRead;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class MarkNotificationAsRead implements MarksNotificationsAsRead
{
    /**
     * Mark the notification as read.
     *
     * @param  \App\Models\User  $user
     * @param  string  $id
     * @return \Illuminate\Notifications\DatabaseNotification
     */
    public function markAsRead(User $user, string $id): DatabaseNotification
    {
        $notification = DatabaseNotification::query()->find($id);

        $notification->markAsRead();

        return $notification;
    }
}
