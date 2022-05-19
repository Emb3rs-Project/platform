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

        $data = $instance->values;

        if($template->id == 15)
            $data = $instance->values["properties"];

        $request = new CharacterizationInput();
        $request->setPlatform(json_encode([
            "type_of_object" => $type,
            "streams" => [$data]
        ]));

        print("Sending  : ");
        dump($request->getPlatform());

         /** @var CharacterizationSourceOutput $feature */
         list($feature, $status) = $client->char_simple($request)->wait();

         if($feature) {
             $values = $instance->values;
             $values["streams"] = $feature->getStreams();
             $instance->values = $values;
             $instance->save();
         }
    }
}
