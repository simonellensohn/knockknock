<?php

namespace Database\Factories;

use App\Models\Bell;
use Illuminate\Database\Eloquent\Factories\Factory;

class RingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bell_id' => Bell::factory(),
            'volume' => $this->faker->numberBetween(1, 10),
        ];
    }
}
