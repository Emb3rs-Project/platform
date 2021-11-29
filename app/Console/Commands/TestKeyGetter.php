<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TestKeyGetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'embers:getkeyList';

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
        $in = '{"metadata":{"start":"b73b2310-266e-42e0-8d51-c503aac751b1","steps":{"b73b2310-266e-42e0-8d51-c503aac751b1":{"module":"CF","module_id":1,"function":"sink:building","function_id":2}},"requirements":{"modules":[1]}},"instance":{"name":"12","height_floor":"2","number_floor":"1","space_heating_type":"Conventional","id":120,"type":"sink","location":[38.73410062293045,-9.132492542266847]}}';
        Redis::rpush('Module_Returns', $in);

        $values = Redis::lrange('Module_Returns', 0, 100);

        var_dump($values);

        return Command::SUCCESS;
    }
}
