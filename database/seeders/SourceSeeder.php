<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Template;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::whereName("Sources")->first();
        if (!$category) $category = Category::create(["name" => "Sources", "type" => "source"]);


        Template::factory()
            ->count(5)
            ->isSource()
            ->for($category)
            ->create();
    }
}
