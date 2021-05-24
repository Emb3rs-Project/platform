<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Template;
use Illuminate\Database\Seeder;
use Log;

// use Log;

class ProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::whereName("Processes")->first();
        if (!$category) $category = Category::create(["name" => "Processes", "type" => "process"]);


        $processes = Template::factory()
            ->count(5)
            ->isProcess()
            ->for($category)
            ->create();

        Log::debug("Created Processes");
    }
}
