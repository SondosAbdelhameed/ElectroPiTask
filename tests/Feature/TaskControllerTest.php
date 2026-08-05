<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task_for_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/projects/{$project->id}/tasks", [
            'title' => 'New Task',
            'description' => 'Task description',
            'priority' => 'Low',
            'status' => 'Todo',
            'due_date' => now()->addWeek()->toDateString(),
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Task Created Successfully.']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'project_id' => $project->id,
        ]);
    }

    public function test_user_cannot_create_task_for_other_users_project(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create();

        Sanctum::actingAs($user);

        $this->postJson("/api/projects/{$project->id}/tasks", [
            'title' => 'Invalid Task',
            'description' => 'Should fail',
            'priority' => 'Low',
            'status' => 'Todo',
            'due_date' => now()->addWeek()->toDateString(),
        ])
            ->assertForbidden();
    }

    public function test_user_can_view_own_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create(['title' => 'Owned Task']);

        Sanctum::actingAs($user);

        $this->getJson("/api/projects/{$project->id}/tasks/{$task->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.title', 'Owned Task');
    }

    public function test_user_cannot_view_task_for_other_users_project(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $task = Task::factory()->for($project)->create();

        Sanctum::actingAs($user);

        $this->getJson("/api/projects/{$project->id}/tasks/{$task->id}")
            ->assertForbidden();
    }

    public function test_user_cannot_view_task_when_project_id_does_not_match(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $otherProject = Project::factory()->for($user)->create();
        $task = Task::factory()->for($otherProject)->create();

        Sanctum::actingAs($user);

        $this->getJson("/api/projects/{$project->id}/tasks/{$task->id}")
            ->assertForbidden();
    }

    public function test_user_can_delete_task_from_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/projects/{$project->id}/tasks/{$task->id}")
            ->assertStatus(200)
            ->assertJson(['message' => 'Task deleted successfully']);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_user_can_list_tasks_for_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        Task::factory()->for($project)->count(2)->create();

        Sanctum::actingAs($user);

        $this->getJson("/api/projects/{$project->id}/tasks")
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_user_can_update_own_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create(['title' => 'Original']);

        Sanctum::actingAs($user);

        $this->putJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'priority' => 'High',
            'status' => 'Todo',
            'due_date' => now()->addDays(2)->toDateString(),
        ])
            ->assertStatus(200)
            ->assertJson(['message' => 'Task updated successfully']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
        ]);
    }

    public function test_user_cannot_update_task_for_other_users_project(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $task = Task::factory()->for($project)->create();

        Sanctum::actingAs($user);

        $this->putJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'title' => 'Unauthorized Update',
            'description' => 'Should fail',
            'priority' => 'Low',
            'status' => 'Todo',
            'due_date' => now()->addDays(2)->toDateString(),
        ])
            ->assertForbidden();
    }

    public function test_show_task_not_found_returns_404(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $this->getJson("/api/projects/{$project->id}/tasks/999")
            ->assertStatus(404)
            ->assertJson(['message' => 'Task not found.']);
    }

    public function test_user_cannot_delete_task_for_other_users_project(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $task = Task::factory()->for($project)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/projects/{$project->id}/tasks/{$task->id}")
            ->assertForbidden();
    }
}
