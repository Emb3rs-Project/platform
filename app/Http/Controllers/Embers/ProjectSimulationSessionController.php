<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\IntegrationReport;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectSimulationSessionController extends Controller
{
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $session = SimulationSession::findOrFail($id);
        $session->load(['simulation', 'simulation.project']);
        $reports = IntegrationReport::where('simulation_uuid', 'like', $session->simulation_uuid)
            ->orderBy('created_at')
            ->get();
        $session->simulation->extra = [];
        $reportsToReturn = $reports->map(function($item) {
            $item->data = "{}";
            $item->output = "{}";
            return $item;
        });
        return Inertia::render('Simulations/SimulationSessionShow', ["session" => $session, "reports" => $reportsToReturn]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $session = SimulationSession::findOrFail($id);
        $simulation = $session->simulation;
        $reports = IntegrationReport::where('simulation_uuid', 'like', $session->simulation_uuid)->get();

        foreach($reports as $report)
            $report->delete();

        $session->delete();

        return redirect()->route('projects.simulations.show', ['simulation' => $simulation->id, 'project' => $simulation->project_id]);
    }


}
