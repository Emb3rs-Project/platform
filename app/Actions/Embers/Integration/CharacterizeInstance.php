<?php

namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\CharacterizesInstances;
use App\Models\Instance;
use Cf\CFModuleClient;
use Cf\CharacterizationInput;
use Cf\CharacterizationSinkOutput;
use Illuminate\Support\Facades\Redis;

class CharacterizeInstance implements CharacterizesInstances
{
    public function characterize(Instance $instance): void
    {
        $client = new CFModuleClient(
            'vali.pantherify.dev:50051',
            [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]
        );

        switch ($instance->template_id) {
            case 14:
            case 15:
                $this->simple_user($instance, $client);
                break;

            case 2:
                $this->building($instance, $client);
                break;
            case 8:
                $this->greenhouse($instance, $client);
                break;

            default:
                dump("NOT DEFINED!");
                break;
        }
    }

    private function simple_user(Instance $instance, CFModuleClient $client)
    {
        $template = $instance->template;
        $type = $template->category->type;

        $data = $instance->values;

        // TODO: Normalize Values Structure
        if ($template->id == 15)
            $data = $instance->values["properties"];

        $request = new CharacterizationInput();
        $request->setPlatform(json_encode([
            "type_of_object" => $type,
            "streams" => [$data]
        ]));

        /** @var CharacterizationSourceOutput $feature */
        list($feature, $status) = $client->char_simple($request)->wait();


        if ($feature) {
            $characterization = [];
            $values = $instance->values;
            $characterization["streams"] = json_decode($feature->getStreams());
            $values['characterization'] = $characterization;
            $instance->values = $values;
            $instance->save();
        }
    }

    private function building(Instance $instance, CFModuleClient $client)
    {
        $data = $instance->values;
        $location = $instance->location->data;
        $data["location"] = $location['center'];

        $request = new CharacterizationInput();
        $request->setPlatform(json_encode($data));

        /** @var CharacterizationSinkOutput $feature */
        list($feature, $status) = $client->char_building($request)->wait();

        if($feature) {
            $characterization = [];
            $values = $instance->values;
            $characterization["streams"] = [json_decode($feature->getColdStream()), json_decode($feature->getHotStream())];
            $values['characterization'] = $characterization;
            $instance->values = $values;
            $instance->save();
        }

    }

    private function greenhouse(Instance $instance, CFModuleClient $client)
    {
        $data = $instance->values;
        $location = $instance->location->data;
        $data["location"] = $location['center'];

        $request = new CharacterizationInput();
        $request->setPlatform(json_encode($data));

        /** @var CharacterizationSinkOutput $feature */
        list($feature, $status) = $client->char_greenhouse($request)->wait();

        if($feature) {
            $characterization = [];
            $values = $instance->values;
            $characterization["streams"] = [json_decode($feature->getColdStream()), json_decode($feature->getHotStream())];
            $values['characterization'] = $characterization;
            $instance->values = $values;
            $instance->save();
        }

    }
}
