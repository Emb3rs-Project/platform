<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface DestroysNotifications
{
    /**
     * Destroys the notification for the currently logged in user.
     *
     * @param  \App\Models\User  $user
     * @param  string  $id
     * @return void
     */
    public function destroy(User $user, string $id): void;
}
