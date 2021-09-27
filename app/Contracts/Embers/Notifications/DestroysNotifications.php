<?php

namespace App\Contracts\Embers\Notifications;

use App\Models\User;

interface DestroysNotifications
{
    /**
     * Destroys the notification for the currently logged in user.
     */
    public function destroy(User $user, string $id): void;
}
