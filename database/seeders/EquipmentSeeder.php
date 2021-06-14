<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::whereType("equipment")->first();

        $templates = [
            ["name" => "Boiler/Burner/Heater", "category_id" => $category->id]
        ];

        //TODO: Need TemplateProperties
    }
}
