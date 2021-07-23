<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Sources\SharesSources;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShareSourceController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, int $id)
    {
        $source = app(SharesSources::class)->share($request->user(), $id);

        // return [
        //     "slideOver" => 'Objects/Sinks/SinkShare',
        //     "props" => [
        //         "instance" => $sink
        //     ]
        // ];

        return response()->json([
            "source" => $source
        ]);
    }
}
