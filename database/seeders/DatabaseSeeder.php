<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (Task::count() === 0) {
            $tasks = [
                'Learn about Slash',
                'Answer emails for 30 minutes',
                'Buy flight to Portugal',
            ];

            foreach ($tasks as $position => $title) {
                Task::create(['title' => $title, 'position' => $position + 1]);
            }
        }
    }
}
