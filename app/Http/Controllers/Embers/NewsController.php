<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\News\IndexesNews;
use App\Contracts\Embers\News\ShowsNews;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $news = app(IndexesNews::class)->index($request->user());

        return Inertia::render('News/NewsIndex', [
            'news' => $news
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $newsId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $newsId)
    {
        $news = app(ShowsNews::class)->show($request->user(), $newsId);

        return Inertia::render('News/NewsDetails', [
            'news' => $news
        ]);
    }
}
