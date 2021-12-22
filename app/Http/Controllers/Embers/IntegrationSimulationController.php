<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegrationSimulationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        info($request->all());
    }
}
