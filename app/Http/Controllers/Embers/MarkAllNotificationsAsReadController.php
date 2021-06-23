<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Notifications\MarksAllNotificationsAsRead;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarkAllNotificationsAsReadController extends Controller
{
    /**
     * Mark all the available notifications as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        app(MarksAllNotificationsAsRead::class)->markAllAsRead($request->user());

        return back(303);
    }
}
