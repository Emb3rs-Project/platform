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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(SimulationSession $session, IntegrationReport $report)
    {
        $data = json_decode($report->output);
        return view('reports.integration-report', ["report" => $data])->render();
    }
}
