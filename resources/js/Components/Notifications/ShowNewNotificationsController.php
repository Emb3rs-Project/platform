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
   * @return \Illuminate\Http\JsonResponse
   */
  public function __invoke(Request $request)
  {
    [
      $unreadNotifications,
      $readNotifications
    ] = app(IndexesNotifications::class)->index($request->user());

    $unreadNotificationsCount = $unreadNotifications->count();

    return response()->json([
      'unreadNotifications' => $unreadNotifications,
      'unreadNotificationsCount' => $unreadNotificationsCount
    ]);
  }
}
