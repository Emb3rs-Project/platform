<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Notifications;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $news = News::whereTeamId(Auth::user()->currentTeam->id)->get();

        $notifications = Notifications::whereTeamId(Auth::user()->currentTeam->id)->get();
        return Inertia::render('Dashboard', [
            'news' => $news,
            'notifications' => $notifications
        ]);
    }
}
