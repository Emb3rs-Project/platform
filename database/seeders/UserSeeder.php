<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $davidsf = User::factory()->create([
            'name' => 'David Fernandes',
            'email' => 'davidsf@panterify.com',
            'password' => Hash::make('scob4volk_gnix*BUFT')
        ]);

        Team::forceCreate([
            'user_id' => $davidsf->id,
            'name' => explode(' ', $davidsf->name, 2)[0]."'s Personal",
            'personal_team' => true,
        ]);

        $geocfu = User::factory()->create([
            'name' => 'George Mantellos',
            'email' => 'gmantellos@pdmfc.com',
            'password' => Hash::make('scob4volk_gnix*BUFT')
        ]);

        Team::forceCreate([
            'user_id' => $geocfu->id,
            'name' => explode(' ', $geocfu->name, 2)[0]."'s Personal",
            'personal_team' => true,
        ]);
    }
}
