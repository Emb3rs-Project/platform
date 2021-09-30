<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\RemovesAllNotifications;
use App\Models\User;

class RemoveAllNotifications implements RemovesAllNotifications
{
    /**
     * Remove all the available notifications.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function remove(User $user): void
    {
        $user->notifications()->delete();
    }
}
