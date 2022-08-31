<?php


namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\StartsSimulations;
use App\Events\Embers\SimulationFinished;
use App\Events\Embers\SimulationUpdate;
use App\Models\IntegrationReport;
use App\Models\Simulation;
use App\Models\SimulationSession;
use App\Models\User;
use App\Notifications\Embers\SimulationNotification;
use Manager\ManagerClient;
use Manager\StartSimulationRequest;

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

        $session->simulation->changeStatusTo(Simulation::RUNNING);

        list($result, $status) = $client->StartSimulation($request)->wait();

        logger()?->error('[simulatison_output]:', [$status]);
        $reportError = IntegrationReport::where('simulation_uuid', 'like', $session->simulation_uuid)->whereNotNull('errors')->count();

        if ($reportError > 0) {
            $session->simulation->changeStatusTo(Simulation::ERROR);
        } else {
            $session->simulation->changeStatusTo(Simulation::COMPLETED);
        }

        $user = User::find($session->simulation->requested_by);
        $tag = [['name' => "Simulation #$session->simulation_uuid", 'path' => 'session.show']];
        broadcast(new SimulationFinished($session->simulation->id));
        $user->notify(new SimulationNotification($user, $session->simulation->project->teams->first(),
            $tag,
            'The Simulation has finished',
            $session->id
        ));
    }
}
