<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CreateAdminRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => 'David Fernandes',
            "email" => "davidsf@pantherify.com",
            "password" => bcrypt(env("ADMIN_PASSWORD", "12345678")),
            "username" => "dfernandes",
        ]);

        User::create([
            "name" => 'George Mantellos',
            "email" => "gmantellos@pdmfc.com",
            "password" => bcrypt(env("GEOCFU_PASSWORD", "12345678")),
            "username" => "geocfu",
        ]);
    }
}
