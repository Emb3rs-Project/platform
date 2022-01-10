<?php

namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\CharacterizesInstances;
use App\Models\Instance;
use Illuminate\Support\Facades\Redis;

class CharacterizeInstance implements CharacterizesInstances
{
    public function characterize(Instance $instance): void
    {
        // Prepare data for characterization, if trigger exists
        $template = $instance->template;

        if (is_null($template->triggers)) return;

        if ($template->triggers->doesntExist()) return;

        $instanceData = $instance->getInstanceData();

        $triggerData = [
            "metadata" => $template->triggers['data'],
            "instance" => $instanceData
        ];

        Redis::publish('characterization', json_encode($triggerData));
    }
}
