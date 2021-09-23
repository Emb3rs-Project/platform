<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Notifications\IndexesNotifications;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        [
            $notifications,
            $readNotifications,
            $unreadNotifications
        ] = app(IndexesNotifications::class)->index($request->user());

        return Inertia::render('Notifications/NotificationIndex', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        // call action
        return redirect()->route('notifications.index');
    }
}
