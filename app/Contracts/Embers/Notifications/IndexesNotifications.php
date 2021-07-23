<?php

namespace App\Contracts\Embers\Notifications;

interface IndexesNotifications
{
    /**
     * Display all the available notifications for the currently logged in user.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user);
}
