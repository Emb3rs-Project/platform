<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Template;
use Illuminate\Database\Seeder;

class SinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::whereName("Sinks")->first();
        if (!$category) $category = Category::create(["name" => "Sinks", "type" => "sink"]);


        Template::factory()
            ->count(5)
            ->isSink()
            ->for($category)
            ->create();
    }
}
