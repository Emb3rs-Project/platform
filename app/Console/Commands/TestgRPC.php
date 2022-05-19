<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Manager\ManagerModuleClient;
use Manager\StartSimulationRequest;
use Manager\StartSimulationResponse;

use Cf\CFModuleClient;
use Cf\CharacterizationInput;
use Cf\CharacterizationSourceOutput;
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
        $client = new CFModuleClient(
            'vali.pantherify.dev:50051', [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]
        );

        // $request = new StartSimulationRequest();
        // $request->setSimulationUuid(Str::uuid()->toString());
        // $request->setSimulationMetadata("");
        // $request->setInitialData("");
        $request = new CharacterizationInput();
        $request->setPlatform(json_encode([
            "type_of_object" => "source",
            "streams" => [
                [ "name"=> "BIOPAR",
                "fluid"=> "flue_gas",
                "supply_temperature"=> "220",
                "target_temperature"=> "120",
                "fluid_cp"=> 1.4,
                "capacity"=> 438.57522,
                "daily_periods"=> "[[0,24]]",
                "saturday_on"=> 1,
                "sunday_on"=> 1,
                "shutdown_periods"=> [[274,300],[335,365]]]
            ]
            ]));

        print("Sending  : ");
        dump($request->getPlatform());

        /** @var StartSimulationResponse $feature */
        // list( $feature , $status) = $client->StartSimulation($request)->wait();

        // print("Received : ");
        // dump($feature->getSimulationUuid());

        /** @var CharacterizationSourceOutput $feature */
        list($feature, $status) = $client->char_simple($request)->wait();
        print($status);
        print("Received : ");
        if($feature)
            dump($feature->getStreams());

        return 0;
    }
}
