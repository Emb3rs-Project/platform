<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class SimulationSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'embers:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribes to Redis Channels';

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
        Redis::subscribe(['simulations'], function ($message) {
            echo $message;
        });

        return Command::SUCCESS;
    }
}
