<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\DestroysNotifications;
use App\Models\User;
use Ramsey\Uuid\Rfc4122\UuidV4;

class DestroyNotification implements DestroysNotifications
{
    /**
     * Destroys the notification for the currently logged in user.
     */
    public function destroy(User $user, $id): void
    {
        info(get_class($id));

        $notification = $user->notifications->firstOrFail($id);

        $notification->delete();
    }
}
