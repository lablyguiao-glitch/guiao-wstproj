<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed a few sample tasks so the dashboard has something to show.
     */
    public function run(): void
    {
        $tasks = [
            ['Submit Laravel mini project', 'Push the repository to GitHub and submit the URL.', Task::PENDING, today()->addDays(6)],
            ['Review Eloquent relationships', 'Read the docs section on one-to-many relationships.', Task::PENDING, today()->addDays(2)],
            ['Pay internet bill', null, Task::PENDING, today()],
            ['Finish statistics worksheet', 'Problems 1–15 from chapter 4.', Task::PENDING, today()->subDays(2)],
            ['Return library book', 'Clean Code, due at the main library desk.', Task::PENDING, today()->subDays(5)],
            ['Set up local database', 'Configure .env and run the migrations.', Task::COMPLETED, today()->subDay()],
            ['Buy notebook', null, Task::COMPLETED, null],
        ];

        foreach ($tasks as [$name, $description, $status, $dueDate]) {
            Task::create([
                'task_name' => $name,
                'description' => $description,
                'status' => $status,
                'due_date' => $dueDate,
            ]);
        }
    }
}
