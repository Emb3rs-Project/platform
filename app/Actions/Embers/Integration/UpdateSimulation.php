<?php


namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\StartsSimulations;
use App\Events\Embers\SimulationFinished;
use App\Models\IntegrationReport;
use App\Models\Simulation;
use App\Models\SimulationSession;
use App\Models\User;
use App\Notifications\Embers\SimulationNotification;
use Manager\ManagerClient;
use Manager\UpdateSimulationRequest;

class UpdateSimulation implements StartsSimulations
{

    public function run_simulation(SimulationSession $session, $payload = []): void
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


        $initialData['buildmodel']['platform_technologies'] = $payload['river_data']['buildmodel_platform_input']['platform_technologies'];

        $request = new UpdateSimulationRequest();
        $request->setSimulationUuid(str($session->simulation_uuid)->toString());
        $request->setSimulationMetadata(json_encode($session->simulation->simulationMetadata->data));
        $request->setData(json_encode($initialData));

        $session->simulation->changeStatusTo(Simulation::RUNNING);

        list($result, $status) = $client->UpdateSimulation($request)->wait();

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

    private function convertTimeSliceFrom($type)
    {
        $timeslices = [
            'monthly' => range(1, 12),
            'weekly' => range(1, 48),
            'daily' => range(1, 366),
            'quad-hourly' => range(1, 2196),
            'bi-hourly' => range(1, 4392),
            'hourly' => range(1, 8784),
        ];

        return $timeslices[$type] ?? [];
    }
}
