<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Search\QueriesSearch;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuerySearchController extends Controller
{
    /**
     * Get all the available unread notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __invoke(Request $request): JsonResponse
    {
        abort_unless($request->filled('keyword'), Response::HTTP_BAD_REQUEST);

        $results = app(QueriesSearch::class)->query($request->user(), $request->input('keyword'));

        return response()->json($results);
    }
}
