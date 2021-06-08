<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genericCategories = [
            ["name" => "Sources", "type" => "source"],
            ["name" => "Sinks", "type" => "sinks"],
            ["name" => "Processes", "type" => "process"],
            ["name" => "Equipments", "type" => "equipment"]
        ];

        foreach ($genericCategories as $category) {
            Category::create($category);
        }
    }
}
