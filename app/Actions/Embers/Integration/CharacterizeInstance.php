<?php

namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\CharacterizesInstances;
use App\Models\Instance;
use Cf\CFModuleClient;
use Cf\CharacterizationInput;
use Illuminate\Support\Facades\Redis;

class CharacterizeInstance implements CharacterizesInstances
{
    public function characterize(Instance $instance): void
    {
        // Prepare data for characterization, if trigger exists
        $template = $instance->template;
        $type = $template->category->type;

        $client = new CFModuleClient(
            'vali.pantherify.dev:50051', [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]
        );

        $request = new CharacterizationInput();
        $request->setPlatform(json_encode([
            "type_of_object" => $type,
            "streams" => [$instance->values]
        ]));

        print("Sending  : ");
        dump($request->getPlatform());

         /** @var CharacterizationSourceOutput $feature */
         list($feature, $status) = $client->char_simple($request)->wait();

         if($feature) {
             $instance->values["streams"] = $feature->getStreams();
             $instance->save();
         }
    }
}
