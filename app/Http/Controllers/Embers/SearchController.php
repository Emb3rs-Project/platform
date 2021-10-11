<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SearchController extends Controller
{
    /**
     * Get all the available unread notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Search/SearchIndex', [
            'keyword' => $request->input('keyword')
        ]);
    }
}
