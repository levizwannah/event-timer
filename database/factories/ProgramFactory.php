<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            // code is nullable in the table, but for seeded/test data we generate a unique code
            'code' => strtoupper(Str::random(6)),
        ];
    }
}
