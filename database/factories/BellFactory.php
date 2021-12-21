<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BellFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'threshold' => $this->faker->numberBetween(1, 10),
            'user_id' => User::factory(),
        ];
    }
}
