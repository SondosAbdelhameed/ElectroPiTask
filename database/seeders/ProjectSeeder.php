<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::first();

        foreach (ProjectStatus::cases() as $status) {
            Project::factory()->create([
                'user_id' => $user->id,
                'status' => $status,
            ]);
        }

        // create remaining projects using random statuses to reach 10 total
        Project::factory()->count(10 - count(ProjectStatus::cases()))->create([
            'user_id' => $user->id,
        ]);
    }
}
