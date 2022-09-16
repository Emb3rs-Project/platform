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
use Illuminate\Support\Str;
use Manager\ManagerClient;
use Manager\StartSimulationRequest;
use Market\MarketInput;
use Market\MarketModuleClient;
use Market\ShortTermMarketResponse;

class MarketShortTermSimulation implements StartsSimulations
{

    public function run_simulation(SimulationSession $session): void
    {
        $session->load(['simulation', 'simulation.simulationMetadata']);

        $host = config('grpc.grpc_m_mm_host');
        $port = config('grpc.grpc_m_mm_port');

        $client = new MarketModuleClient(
            "$host:$port",
            [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]
        );

        $request = new MarketInput();
        $request->setInput(json_encode($session->simulation->extra['input_data']));

        $output= null;
        $errors= null;

        $session->simulation->createStepFor($session->simulation_uuid, 'SIMULATOR', 'SIMULATION STARTED');

        $session->simulation->changeStatusTo(Simulation::RUNNING);
        list($result, $status) = $client->RunShortTermMarketDirect($request)->wait();
        $session->simulation->changeStatusTo(Simulation::COMPLETED);

        logger()?->error('[simulation_output]:', [$status]);
        if($status->code == 0 ) {

            $output = $this->sanitizeModuleJsonResponse($result->serializeToJsonString());
        } else {
            $errors = ['detail' => $status->details];
        }

        IntegrationReport::create([
            'module' => 'Market Module',
            'function' => 'Short term',
            'data' => $session->simulation->extra['input_data'],
            'output' => $output,
            'errors' => $errors,
            'simulation_uuid' => $session->simulation_uuid,
            'step_uuid' => $session->simulation_uuid,
            'simulation_id' => $session->simulation_id
        ]);

        $session->simulation->createStepFor($session->simulation_uuid, 'SIMULATOR', 'SIMULATION FINISHED');

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

    private function sanitizeModuleJsonResponse(string $response)
    {
        $response = Str::replace('\\','',$response);
        $response = Str::replace('"{','{',$response);
        $response = Str::replace('}"','}',$response);
        $response = Str::replace('"[','[',$response);
        return Str::replace(']"',']',$response);


    }





}


