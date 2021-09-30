<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\DestroysNotifications;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class DestroyNotification implements DestroysNotifications
{
    /**
     * Destroys the notification for the currently logged in user.
     *
     * @param  \App\Models\User  $user
     * @param  string  $id
     * @return void
     */
    public function destroy(User $user, string $id): void
    {
        $notification = DatabaseNotification::query()->find($id);

        $notification->delete();
    }
}
