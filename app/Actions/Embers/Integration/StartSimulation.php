<?php


namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\StartsSimulations;
use App\Models\Instance;
use App\Models\SimulationSession;
use Manager\ManagerClient;
use Manager\StartSimulationRequest;
use Str;

class StartSimulation implements StartsSimulations
{

    public function run_simulation(SimulationSession $session): void
    {
        $session->load(['simulation', 'simulation.simulationMetadata']);

        $client = new ManagerClient(
            'vali.pantherify.dev:50041',
            [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]
        );

        $request = new StartSimulationRequest();
        $request->setSimulationUuid(str($session->simulation_uuid)->toString());
        $request->setInitialData(json_encode($session->simulation->extra));
        $request->setSimulationMetadata(json_encode($session->simulation->simulationMetadata->data));

        $client->StartSimulation($request)->wait();
    }
}
