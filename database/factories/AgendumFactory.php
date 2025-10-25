<?php

namespace Database\Factories;

use App\Models\Agendum;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendumFactory extends Factory
{
    protected $model = Agendum::class;

    public function definition(): array
    {
        return [
            // By default create a Program if none is provided
            'program_id' => Program::factory(),
            'title' => $this->faker->sentence(4),
            // description column is string(2000) nullable
            'description' => $this->faker->optional()->text(2000),
            // duration in minutes (1..60)
            'duration' => $this->faker->numberBetween(1, 60),
            // order for sequencing (1..10)
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}
