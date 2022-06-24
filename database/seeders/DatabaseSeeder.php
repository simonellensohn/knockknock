<?php

namespace Database\Seeders;

use App\Models\Bell;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'first_name' => 'Simon',
            'last_name' => 'Ellensohn',
            'email' => 'ellensohn.simon@gmail.com',
            'password' => Hash::make('knockknock'),
            'admin' => true,
        ]);

        Bell::factory()->for($user)->create([
            'name' => 'Main door',
            'min_volume' => 9,
            'max_volume' => 11,
        ]);

        Bell::factory()->for($user)->create([
            'name' => 'Apartment door',
            'min_volume' => 5,
            'max_volume' => 7,
        ]);
    }
}
