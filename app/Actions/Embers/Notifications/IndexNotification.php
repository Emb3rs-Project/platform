<?php

namespace App\Actions\Embers\Notifications;

use App\Contracts\Embers\Notifications\IndexesNotifications;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

class IndexNotification implements IndexesNotifications
{
    /**
     * Display all the available notifications.
     */
    public function index(User $user): array
    {
        $unreadNotifications = $user->unreadNotifications->map(function ($notification) {
            return Arr::except($notification, ['type', 'notifiable_type', 'notifiable_id']);
        });

        $readNotifications = $user->readNotifications->map(function ($notification) {
            return Arr::except($notification, ['type', 'notifiable_type', 'notifiable_id']);
        });

        return [
            $unreadNotifications,
            $readNotifications,
        ];
    }
}
