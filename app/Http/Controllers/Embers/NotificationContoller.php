<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Notifications\DestroysNotifications;
use App\Contracts\Embers\Notifications\IndexesNotifications;
use App\Contracts\Embers\Notifications\MarksAllNotificationsAsRead;
use App\Contracts\Embers\Notifications\MarksNotificationsAsRead;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Notifications\Embers\ObjectNotification;
use Illuminate\Http\RedirectResponse;
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
            $unreadNotifications,
            $readNotifications,
        ] = app(IndexesNotifications::class)->index($request->user());

        return Inertia::render('Notifications/NotificationIndex', [
            'unreadNotifications' => $unreadNotifications,
            'readNotifications' => $readNotifications
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        app(MarksNotificationsAsRead::class)->markAsRead($request->user(), $id);

        return back(303);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        app(DestroysNotifications::class)->destroy($request->user(), $id);

        return back(303);
    }

    public function objectNotify(User $user, Team $team, array $tag, string $message, $id)
    {
        $users = $team->allUsers();

        foreach ($users as $userTeam) {
            $userTeam->notify(new ObjectNotification($user, $team, $tag, $message, $id));
        }
    }
}
