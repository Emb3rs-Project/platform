<?php

namespace App\Actions\Embers\Integration;

use App\Models\Instance;
use Illuminate\Support\Facades\Redis;

class Characterization
{
    public static function characterize(Instance $instance)
    {
        // Prepare data for characterization, if trigger exists
        $template = $instance->template;

        if ($template->triggers->doesntExist()) return;

        $instanceData = $instance->getInstanceData();

        $triggerData = [
            "metadata" => $template->triggers['data'],
            "instance" => $instanceData
        ];

        Redis::publish('characterization', json_encode($triggerData));
    }
}
