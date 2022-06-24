<?php

namespace Database\Factories;

use App\Models\Bell;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Bell> */
class BellFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'min_volume' => $this->faker->randomFloat(2, 1, 10),
            'max_volume' => $this->faker->randomFloat(2, 1, 10),
            'user_id' => User::factory(),
        ];
    }
}
