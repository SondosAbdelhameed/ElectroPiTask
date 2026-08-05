<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $statuses = TaskStatus::cases();
        $priorities = TaskPriority::cases();

        Project::chunk(10, function ($projects) use ($statuses, $priorities) {
            foreach ($projects as $project) {
                $tasks = [];
                for ($i = 0; $i < 10; $i++) {
                    $tasks[] = [
                        'title' => 'Task ' . ($i + 1) . ' for ' . $project->name,
                        'description' => 'Auto-generated task for project ' . $project->name . '.',
                        'project_id' => $project->id,
                        'status' => $statuses[$i % count($statuses)],
                        'priority' => $priorities[$i % count($priorities)],
                        'due_date' => now()->addDays(rand(1, 30))->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                
                $project->tasks()->createMany($tasks);
            }
        });
    }
}
