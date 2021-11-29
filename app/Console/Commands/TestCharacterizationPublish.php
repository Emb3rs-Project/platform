<?php

namespace App\Console\Commands;

use App\Models\Instance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TestCharacterizationPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'embers:testcharpublish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $instance = Instance::findOrFail(120);

        // Prepare data for characterization, if trigger exists
        $template = $instance->template;
        if ($template->triggers) {
            $instanceData = $instance->getInstanceData();

            $triggerData = [
                "metadata" => $template->triggers['data'],
                "instance" => $instanceData
            ];

            Redis::publish('characterization', json_encode($triggerData));
        }

        return Command::SUCCESS;
    }
}
