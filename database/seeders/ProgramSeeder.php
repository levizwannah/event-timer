<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Program::factory()->create([
            'title' => 'Youth Empowerment Summit 2025',
            'code' => 'YES2025',
        ]);

        Program::factory()->create([
            'title' => 'Church Anniversary Celebration',
            'code' => 'CAC2025',
        ]);

        Program::factory()->create([
            'title' => 'Community Outreach Program',
            'code' => 'COP2025',
        ]);
    }
}
