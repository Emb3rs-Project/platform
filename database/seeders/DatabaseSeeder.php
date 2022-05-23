<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Instance;
use App\Models\Property;
use App\Models\Template;
use App\Models\TemplateProperty;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       Instance::truncate();
       Template::truncate();
       TemplateProperty::truncate();
       Property::truncate();
       Unit::truncate();
       Category::truncate();

       DB::table('unit_property')->truncate();
    }
}
