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
        //$this->truncate_tables();
        //$this->load_sql_files();

    }

    private function truncate_tables() {
//        $this->command->info('Truncating Tables...');
//        $this->command->info('Truncating Instance');
//        Instance::truncate();
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
    }


    private function load_sql_files()
    {
        $this->command->info('Loading SQL Files...');
        $sql_files = [
            'database/seeders/SQL/CF - Category.SQL',
            'database/seeders/SQL/CF - Property.SQL',
            'database/seeders/SQL/CF - Template.SQL',
            'database/seeders/SQL/CF - TemplateProperty.SQL',
            'database/seeders/SQL/CF - unit.sql',
            'database/seeders/SQL/CF - UnitProperty.SQL'
        ];

        foreach ($sql_files as $sql_file) {
            $this->load_sql_file($sql_file);
        }
    }

    private function load_sql_file($sql_file)
    {
        $this->command->info("Loading SQL File $sql_file");
        DB::unprepared(
            file_get_contents($sql_file)
        );
        $this->command->info("Loading SQL File $sql_file [Done!]");
    }
}
