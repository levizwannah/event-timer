<?php

namespace Database\Seeders;

use App\Models\Agendum;
use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgendumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program = Program::first(); // attach to the first program

        if ($program) {
            $agenda = [
                ['title' => 'Opening Prayer', 'duration' => 5, 'order' => 1],
                ['title' => 'Welcome Address', 'duration' => 10, 'order' => 2],
                ['title' => 'Guest Speaker Session', 'duration' => 30, 'order' => 3],
                ['title' => 'Vote of Thanks', 'duration' => 10, 'order' => 4],
                ['title' => 'Closing Remarks', 'duration' => 5, 'order' => 5],
            ];

            foreach ($agenda as $item) {
                Agendum::create([
                    'program_id' => $program->id,
                    'title' => $item['title'],
                    'duration' => $item['duration'],
                    'order' => $item['order'],
                ]);
            }
        }
    }
}
