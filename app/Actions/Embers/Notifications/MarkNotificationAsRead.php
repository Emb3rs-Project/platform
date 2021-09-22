<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\MarksNotificationAsRead;
use App\Models\User;

class MarkNotificationAsRead implements MarksNotificationAsRead
{
    /**
     * Display all the available notifications for the currently logged in user.
     *
     * @param  mixed  $user
     * @return array
     */
    public function markAsRead(User $user, int $id)
    {
        $notification = $user->notifications->firstOrFail($id);

        $notification->markAsRead();

        return $notification;
    }
}
