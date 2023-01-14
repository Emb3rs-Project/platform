<?php

namespace App\Actions\Embers\Integration;

use App;
use App\Contracts\Embers\Integration\CharacterizesInstances;
use App\Models\Instance;
use Cf\CFModuleClient;
use Cf\CharacterizationInput;
use Cf\CharacterizationSinkOutput;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redis;

class CharacterizeInstance implements CharacterizesInstances
{
    /**
     * @param Instance $instance
     * @param array $oldInstance
     * @param bool $throw
     * @return RedirectResponse|void
     * @throws \JsonException
     * @throws \Exception
     */
    public function characterize(Instance $instance, array $oldInstance = [], bool $throw = false)
    {
        $cf_host = \Config::get("grpc.grpc_cf_host");
        $cf_port = \Config::get("grpc.grpc_cf_port");

        $client = new CFModuleClient(
            "$cf_host:$cf_port",
            [
                'credentials' => \Grpc\ChannelCredentials::createInsecure(),
            ]
        );

        $status = 0;
        switch ($instance->template_id) {
            case 14:
            case 15:
                $status = $this->simple_user($instance, $client);
                break;

            case 2:
                $status = $this->building($instance, $client);
                break;
            case 8:
                $status = $this->greenhouse($instance, $client);
                break;

            default:
                return back()->withErrors([
                    'title' => 'Error',
                    'text' => 'NOT DEFINED!',
                    'type' => 'danger'
                ]);
                break;
        }

        if ($status->code !== 0) {
            if ($oldInstance) {
                $instance->update($oldInstance);
            } else {
                $instance->delete();
                if ($throw) {
                    throw new \Exception($status->details ?? 'General error, please try again.');
                }
            }
            return back()->withErrors([
                'title' => 'Error',
                'text' => $status->details ?? 'General error, please try again.',
                'type' => 'danger'
            ]);
        }
    }

    private function simple_user(Instance $instance, CFModuleClient $client)
    {
        $template = $instance->template;
        $type = $template->category->type;

        $data = $instance->values;

        // TODO: Normalize Values Structure
        if ($template->id == 15) {
            $data = $instance->values["properties"];
        }

        if (array_key_exists('real_hourly_capacity', $data) && !isset($data['real_hourly_capacity'])) {
            unset($data['real_hourly_capacity']);
        }

        if (array_key_exists('real_daily_capacity', $data) && isset($data['real_daily_capacity'])) {
            $data['real_daily_capacity'] = json_decode($data['real_daily_capacity']);
        }

        if (array_key_exists('real_hourly_capacity', $data) && isset($data['real_hourly_capacity'])) {
            $data['real_hourly_capacity'] = json_decode($data['real_hourly_capacity']);
        }

        if (array_key_exists('real_monthly_capacity', $data) && isset($data['real_monthly_capacity'])) {
            $data['real_monthly_capacity'] = json_decode($data['real_monthly_capacity']);
        }

        if (array_key_exists('u_floor', $data) && !isset($data['u_floor'])) {
            unset($data['u_floor']);
        }


        $streams = [];
        if (array_key_exists('additional_streams', $instance->values)) {
            $additionalStreams = collect($instance->values['additional_streams']);
            $streams = $additionalStreams->pluck('data')->all();
        }

        $request = new CharacterizationInput();
        $request->setPlatform(json_encode([
            "type_of_object" => $type,
            "streams" => array_merge([$data], $streams)
        ], JSON_THROW_ON_ERROR));

        /** @var CharacterizationSourceOutput $feature */
        list($feature, $status) = $client->char_simple($request)->wait();
        info('charrr', [$feature, $status]);

        if ($feature) {
            $characterization = [];
            $values = $instance->values;
            $characterization["streams"] = json_decode($feature->getStreams());
            $values['characterization'] = $characterization;
            $instance->values = $values;
            $instance->save();
        }

        return $status;
    }

    private function building(Instance $instance, CFModuleClient $client)
    {
        $data = $instance->values;
        $location = $instance->location->data;
        $data["location"] = $location['center'];

        if (!isset($data['T_cool_on'])) {
            $data['T_cool_on'] = 75;
        }

        if (!isset($data['T_heat_on'])) {
            $data['T_heat_on'] = 45;
        }


        foreach ($data as $key => $value) {
            if (array_key_exists($key, $data) && (!isset($data[$key]) || empty($data[$key]))) {
                if (!in_array($key, ['saturday_on', 'sunday_on', 'shutdown_periods'])) {
                    unset($data[$key]);
                }
            }

        }

        $request = new CharacterizationInput();
        $request->setPlatform(json_encode($data));


        /** @var CharacterizationSinkOutput $feature */
        list($feature, $status) = $client->char_building($request)->wait();

        if ($feature) {
            $characterization = [];
            $values = $instance->values;
            //$characterization["streams"] = [json_decode($feature->getStreams()), json_decode($feature->getHotStream())];
            $characterization["streams"] = json_decode($feature->getHotStream());
            $values['characterization'] = $characterization;
            $instance->values = $values;
            $instance->save();
        }

        return $status;
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

        if ($feature) {
            $characterization = [];
            $values = $instance->values;
            //$characterization["streams"] = [json_decode($feature->getColdStream()), json_decode($feature->getHotStream())];
            $characterization["streams"] = json_decode($feature->getHotStream());
            $values['characterization'] = $characterization;
            $instance->values = $values;
            $instance->save();
        }

        return $status;
    }
}
