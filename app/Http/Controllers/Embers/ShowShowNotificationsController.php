<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Notifications\IndexesNotifications;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowNewNotificationsController extends Controller
{
    /**
     * Get all the available unread notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        [
            $notifications,
            $unreadNotifications
        ] = app(IndexesNotifications::class)->index($request->user());

        $unreadNotificationCount = $unreadNotifications->count();

        return response()->json([
            'unreadNotificationCount' => $unreadNotificationCount
        ]);
    }
}
