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
        $stepResults = IntegrationReport::where('simulation_uuid', $session->simulation_uuid)->orderBy('created_at')->get();

        $reports = [];
        $i = 0;

        foreach ($stepResults as $report) {
            $item = json_decode($report->output, true);
            $weight = [
                'GIS Module' => 1,
                'TEO Module' => 2,
                'Market Module' => 3,
                'Business Module' => 4,

            ];
            if (array_key_exists('report', $item)) {
                $reports[$i]['module'] = $report->module;
                $reports[$i]['report'] = $item['report'];
                $reports[$i]['weight'] = $weight[$report->module] ?? 999;
                $i ++;
            }
        }
        $reports = collect($reports)->sortBy('weight')->groupBy('module')->map->last()->toArray();
        $metadata = [
            'project' => $session->simulation->project->name ?? '',
            'simulation' => $session->simulation->name
        ];
        return view('reports.final-integration-report', ["reports" => $reports, 'metadata' => $metadata])->render();
    }

    public function getMapData(IntegrationReport $session, Request $request)
    {
        $createSession = IntegrationReport::where('simulation_id', $session->simulation_id)
            ->where('simulation_uuid', $session->simulation_uuid)
            ->where('module', $session->module)
            ->where('function', 'create_network')->first();
        $output = json_decode($session->output, true);
        $outputCreate = json_decode($createSession->output, true);
        $data = $session->data;
        $createEdges = json_encode($outputCreate['edges']);
        $createNodes = json_encode($outputCreate['nodes']);
        $edges = json_encode($output['network_solution_edges']);
        $nodes = json_encode($output['network_solution_nodes']);
        $edgesSolution = \Storage::disk('public')->get('edges_solution.json');
        $sinksToMap = json_encode($data['cf_module']['n_demand_list']);
        $sourcesToMap = json_encode($data['cf_module']['n_supply_list']);

        return view('reports.map', [
            "createEdges" => $createEdges,
            "createNodes" => $createNodes,
            "edges" => $edges,
            "nodes" => $nodes,
            "edgesSolution" => $edgesSolution,
            "sinksToMap" => $sinksToMap,
            "sourcesToMap" => $sourcesToMap,

        ])->render();
    }
}
