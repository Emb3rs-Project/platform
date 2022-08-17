<?php


namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\StartsSimulations;
use App\Models\Instance;
use App\Models\IntegrationReport;
use App\Models\SimulationSession;
use Manager\ManagerClient;
use Manager\StartSimulationRequest;
use Str;

class StartSimulation implements StartsSimulations
{

    public function run_simulation(SimulationSession $session): void
    {
        $session->load(['simulation', 'simulation.simulationMetadata']);
        $host = config('grpc.grpc_manager_host');
        $port = config('grpc.grpc_manager_port');
        $client = new ManagerClient(
            "$host:$port",
            [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]
        );

        $initialData = $session->simulation->extra;
        $initialData["project"] = $session->simulation->project;


        $request = new StartSimulationRequest();
        $request->setSimulationUuid(str($session->simulation_uuid)->toString());
        $request->setInitialData(json_encode($initialData));
        $request->setSimulationMetadata(json_encode($session->simulation->simulationMetadata->data));

        list($result, $status) = $client->StartSimulation($request)->wait();
        logger()->error('[Error]:', [$result]);
        if ($status->code == 2) {

             IntegrationReport::create([
                 "module" => "Platform",
                 "function" => "StartSimulation",
                 "errors" => ["message" => $status->details]
             ]);
        }
    }
}
