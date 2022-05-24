<?php


namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\StartsSimulations;
use App\Models\Instance;
use App\Models\SimulationSession;
use Manager\ManagerClient;
use Manager\StartSimulationRequest;

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

        dump($session->simulation->extra);
        exit();

        $request = new StartSimulationRequest();
        $request->setSimulationUuid($session->simulation_uuid);
        $request->setInitialData(json_encode($session->simulation->extra['initial_data']));
        $request->setSimulationMetadata(json_encode($session->simulation->simulationMetadata->data));

        $client->StartSimulation($request);
    }
}
