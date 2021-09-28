<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\RemovesAllNotifications;
use App\Models\User;

class RemoveAllNotifications implements RemovesAllNotifications
{
    /**
     * Remove all the available notifications.
     */
    public function remove(User $user): void
    {
        $user->notifications()->delete();
    }
}
