<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Simulations\MySimulations;
use App\Http\Controllers\Controller;
use App\Models\IntegrationReport;
use App\Models\Simulation;
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
            'projects' => $user->currentTeam->projects?->map(fn($item) => [
                'key' => $item->id,
                'value' => $item->name,
            ]
            )
        ]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getSimulations(Request $request)
    {
        return app(MySimulations::class)->index($request->user());
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getSimulation(Request $request)
    {
        return Simulation::with([
            'project',
            'simulationType',
            'simulationType.unit',
            'simulationMetadata'
        ])->find($request->input('id'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function progress(Request $request, $simulation)
    {
        $simulation = Simulation::with('simulationSessions')->findOrFail($simulation);
        $session = $simulation->simulationSessions->last();
        $report = IntegrationReport::where('simulation_uuid', 'like', $session->simulation_uuid)
            ->orderBy('created_at', 'desc')->orderBy('id', 'desc')
            ->latest()->first();

        return $report['module']. $report['function'] . ' '.$session->simulation_uuid;
    }

}
