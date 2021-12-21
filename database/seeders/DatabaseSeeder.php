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
            'threshold' => 10,
        ]);

        Bell::factory()->for($user)->create([
            'name' => 'Apartment door',
            'threshold' => 7,
        ]);
    }
}
