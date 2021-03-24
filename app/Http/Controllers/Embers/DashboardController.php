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
        $news = News::whereIn('team_id', Auth::user()->teams->pluck('id'));
        $notifications = Notifications::whereIn('team_id', Auth::user()->teams->pluck('id'));
        return Inertia::render('Dashboard', [
            'news' => $news,
            'notifications' => $notifications
        ]);
    }
}
