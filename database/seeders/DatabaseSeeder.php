<?php

namespace Database\Seeders;

use App\Models\Instance;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $instances = Instance::all();
       $instances->truncate();
    }
}
