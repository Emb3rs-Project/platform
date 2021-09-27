<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\DestroysNotifications;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class DestroyNotification implements DestroysNotifications
{
    /**
     * Destroys the notification for the currently logged in user.
     */
    public function destroy(User $user, string $id): void
    {
        $notification = DatabaseNotification::find($id);

        $notification->delete();
    }
}
