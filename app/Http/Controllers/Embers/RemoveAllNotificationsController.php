<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Notifications\RemovesAllNotifications;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RemoveAllNotificationsController extends Controller
{
    /**
     * Destory all the available notifications.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        app(RemovesAllNotifications::class)->remove($request->user());

        return back(303);
    }
}
