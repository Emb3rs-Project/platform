<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\IndexesNotifications;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class IndexNotification implements IndexesNotifications
{
    /**
     * Display all the available notifications for the currently logged in user.
     *
     * @param  mixed  $user
     * @return array
     */
    public function index($user)
    {
        $notifications = $user->notifications->map(function ($notification) {
            return Arr::except($notification, ['type', 'notifiable_type', 'notifiable_id']);
        });

        $unreadNotifications = $user->unreadNotifications->map(function ($unreadNotification) {
            return Arr::except($unreadNotification, ['type', 'notifiable_type', 'notifiable_id']);
        });

        return [
            $notifications,
            $unreadNotifications
        ];
    }
}
