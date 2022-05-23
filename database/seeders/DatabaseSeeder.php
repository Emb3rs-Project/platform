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
        $this->command->info('Truncating Tables...');
        $this->command->info('Truncating Instance');
        Instance::truncate();
        $this->command->info('Truncating Instance Done!');
        $this->command->info('Truncating Template');
        Template::truncate();
        $this->command->info('Truncating Template Done!');
        $this->command->info('Truncating TemplateProperty');
        TemplateProperty::truncate();
        $this->command->info('Truncating TemplateProperty Done!');
        $this->command->info('Truncating Property');
        Property::truncate();
        $this->command->info('Truncating Property Done!');
        $this->command->info('Truncating Unit');
        Unit::truncate();
        $this->command->info('Truncating Unit Done!');
        $this->command->info('Truncating Category');
        Category::truncate();
        $this->command->info('Truncating Category Done!');
        $this->command->info('Truncating unit_property');
        DB::table('unit_property')->truncate();
        $this->command->info('Truncating unit_property Done!');

        $this->command->info('Loading SQL Files...');
        DB::unprepared(
            file_get_contents('database/seeders/SQL/CF - Category.SQL')
        );
    }
}
