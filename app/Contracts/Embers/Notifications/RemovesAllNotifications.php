<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface RemovesAllNotifications
{
    /**
     * Remove all the available notifications.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function remove(User $user): void;
}
