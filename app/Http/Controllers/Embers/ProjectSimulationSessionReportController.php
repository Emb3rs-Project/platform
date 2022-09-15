<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\IntegrationReport;
use App\Models\SimulationSession;
use Illuminate\Http\Request;

class ProjectSimulationSessionReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(SimulationSession $session, IntegrationReport $report)
    {
        $data = json_decode($report->output);
        return view('reports.integration-report', ["report" => $data])->render();
    }

    public function getFinalReport(SimulationSession $session, Request $request)
    {
        $stepResults = IntegrationReport::where('simulation_uuid', $session->simulation_uuid)->get();

        $reports = [];
        $i = 0;

        foreach ($stepResults as $report) {
            $item = json_decode($report->output, true);

            if (array_key_exists('report', $item)) {
                $reports[$i]['module'] = $report->module;
                $reports[$i]['report'] = $item['report'];
                $i ++;
            }
        }
        $metadata = [
            'project' => $session->simulation->project->name ?? '',
            'simulation' => $session->simulation->name
        ];
        return view('reports.final-integration-report', ["reports" => $reports, 'metadata' => $metadata])->render();
    }

    public function getMapData(SimulationSession $session, Request $request)
    {
        $polygon = $session?->simulation?->project?->data;
        $edges = \Storage::disk('public')->get('edges.json');
        $edgesSolution = \Storage::disk('public')->get('edges_solution.json');
        $sinksToMap = \Storage::disk('public')->get('sinks_to_map.json');
        $sourcesToMap = \Storage::disk('public')->get('sources_to_map.json');

        return view('reports.map', [
            "edges" => $edges,
            "edgesSolution" => $edgesSolution,
            "sinksToMap" => $sinksToMap,
            "sourcesToMap" => $sourcesToMap,
            "polygon" => $polygon
        ])->render();
    }
}
