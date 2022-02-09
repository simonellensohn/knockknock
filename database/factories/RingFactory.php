<?php

namespace Database\Factories;

use App\Models\Bell;
use App\Models\Ring;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Ring> */
class RingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bell_id' => Bell::factory(),
            'volume' => $this->faker->numberBetween(1, 10),
            'events' => [],
        ];
    }
}
