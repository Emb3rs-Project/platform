<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Simulations\MySimulations;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MySimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $simulations = app(MySimulations::class)->index($request->user());
        $user = $request->user();

        return Inertia::render('MySimulations/MySimulationIndex', [
            'mySimulations' => $simulations,
            'projects' => $user->currentTeam->projects?->map(fn($item) =>
                     [
                        'key' => $item->id,
                        'value' => $item->name,
                    ]
            )
        ]);
    }

}
