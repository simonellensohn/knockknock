<?php

namespace Database\Factories;

use App\Models\Bell;
use Illuminate\Database\Eloquent\Factories\Factory;

class RingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bell_id' => Bell::factory(),
        ];
    }
}
