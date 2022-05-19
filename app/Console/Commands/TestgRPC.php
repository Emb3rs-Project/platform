<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Manager\ManagerModuleClient;
use Manager\StartSimulationRequest;
use Manager\StartSimulationResponse;
use Str;

class TestgRPC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grpc:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new ManagerModuleClient(
            'localhost:50051', [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]
        );

        $request = new StartSimulationRequest();
        $request->setSimulationUuid(Str::uuid()->toString());
        $request->setSimulationMetadata("");
        $request->setInitialData("");

        print("Sending  : ");
        dump($request->getSimulationUuid());

        /** @var StartSimulationResponse $feature */
        // list( $feature , $status) = $client->StartSimulation($request)->wait();

        // print("Received : ");
        // dump($feature->getSimulationUuid());

        $client->StartSimulation($request);

        return 0;
    }
}
