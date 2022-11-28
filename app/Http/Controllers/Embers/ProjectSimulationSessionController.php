<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\IntegrationReport;
use App\Models\Simulation;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use MongoDB\Driver\Session;

class ProjectSimulationSessionController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Request $request, $id): Response
    {
        $session = SimulationSession::findOrFail($id);
        $session->load(['simulation', 'simulation.project', 'challenge']);
        $reports = IntegrationReport::where('simulation_uuid', 'like', $session->simulation_uuid)
            ->orderBy('created_at')
            ->get();
        $challenges = $request->user()->challenges()->get();
        $solverModules = [
            'GIS Module' => optional($session->simulation->extra)['solver_gis'],
            'TEO Module' => optional($session->simulation->extra)['solver_teo'],
            'Market Module' => optional($session->simulation->extra)['solver_market']
        ];
        $session->simulation->extra = [];
        $reportsHTML = [];
        $reportsToReturn = $reports->map(function ($item) use (&$reportsHTML) {

            $output = collect(json_decode($item->output, true));
            $reportsHTML[$item->id] = $output->has('report') && !empty($output->get('report'));
            $item->data = ["Loading..."];
            $item->output = '["Loading..."]';
            return $item;
        });
        return Inertia::render('Simulations/SimulationSessionShow', [
            "session" => $session,
            "reports" => $reportsToReturn,
            'reportsHtml' => $reportsHTML,
            'challenges' => $challenges,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $session = SimulationSession::findOrFail($id);
        $simulation = $session->simulation;
        $reports = IntegrationReport::where('simulation_uuid', 'like', $session->simulation_uuid)->get();

        foreach ($reports as $report)
            $report->delete();

        $session->delete();

        return redirect()->route('projects.simulations.show', ['simulation' => $simulation->id, 'project' => $simulation->project_id]);
    }

    public function jsonReport($type, $id)
    {
        if ($type === 'extra') {
            $simulation = Simulation::find($id);
            return $simulation->extra;
        }
        $report = IntegrationReport::find($id);

        return $report[$type];
    }

    public function csvReport(Request $request, SimulationSession $session)
    {
        $isJson = $request->input('isJson');
        $session->load('simulation');

        if ($isJson) {
            return $this->downloadJson($session);
        }

        $jsonans = $session->simulation->extra;
        $zip_file = 'data.zip';

        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);


        $csv = 'sink.csv';
        $file_pointer = fopen($csv, 'w');
        $keys = collect($jsonans['sinks'][0]['values'])->except('characterization')->keys()->toArray();
        $keys[] = 'template';
        $keys[] = 'latitude';
        $keys[] = 'logitude';
        fputcsv($file_pointer, $keys);
        foreach ($jsonans['sinks'] as $i) {
            $data = collect($i['values'])->except('characterization')->toArray();
            $data['template'] = $i['template']['name'];
            $data['latitude'] = $i['location']['data']['center'][0];
            $data['longitude'] = $i['location']['data']['center'][1];
            // Write the data to the CSV file
            fputcsv($file_pointer, $data);
        }

        $zip->addFile('sink.csv', 'sink.csv');
        fclose($file_pointer);

        $csv = 'source.csv';
        $file_pointer = fopen($csv, 'w');
        $keys = collect($jsonans['sources'][0]['values']['properties'])->keys()->toArray();
        $keys[] = 'template';
        $keys[] = 'latitude';
        $keys[] = 'logitude';
        fputcsv($file_pointer, $keys);
        foreach ($jsonans['sources'] as $i) {
            $data = collect($i['values']['properties'])->toArray();
            $data['template'] = $i['template']['name'];

            $data['latitude'] = $i['location']['data']['center'][0];
            $data['longitude'] = $i['location']['data']['center'][1];
            // Write the data to the CSV file
            fputcsv($file_pointer, $data);
        }
        $zip->addFile($csv, $csv);
        fclose($file_pointer);

        $zip->close();
        unlink('source.csv');
        unlink('sink.csv');
        $base = base64_encode(file_get_contents('data.zip'));
        unlink('data.zip');
        return $base;
    }


    protected function downloadJson($session)
    {
        $reports = IntegrationReport::where('simulation_uuid', 'like', $session->simulation_uuid)->get();
        $fullJSON = [];
        foreach ($reports as $report) {
            $fullJSON[$report->module][$report->function] = [
                'input' => $report->data,
                'output' => json_decode($report->output)
            ];
        }

        return $fullJSON;
    }


}
