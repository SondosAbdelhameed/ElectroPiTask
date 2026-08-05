<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_projects(): void
    {
        $this->getJson('/api/projects')
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_create_project(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/projects', [
            'name' => 'My New Project',
            'description' => 'A project created by test',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Project Created Successfully.']);

        $this->assertDatabaseHas('projects', [
            'name' => 'My New Project',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_view_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create(['name' => 'Owned Project']);

        Sanctum::actingAs($user);

        $this->getJson("/api/projects/{$project->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'Owned Project');
    }

    public function test_user_cannot_view_project_they_do_not_own(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create();

        Sanctum::actingAs($user);

        $this->getJson("/api/projects/{$project->id}")
            ->assertForbidden();
    }

    public function test_user_can_update_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create(['name' => 'Original Name']);

        Sanctum::actingAs($user);

        $this->putJson("/api/projects/{$project->id}", [
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ])
            ->assertStatus(200)
            ->assertJson(['message' => 'Project Updated Successfully.']);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_user_cannot_delete_other_users_project(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/projects/{$project->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_authenticated_user_can_list_their_projects(): void
    {
        $user = User::factory()->create();
        Project::factory()->for($user)->count(2)->create();
        Project::factory()->for(User::factory()->create())->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/projects')
            ->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_user_can_delete_own_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();

        Sanctum::actingAs($user);

        $this->deleteJson("/api/projects/{$project->id}")
            ->assertStatus(200)
            ->assertJson(['message' => 'Project deleted successfully']);

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_user_cannot_update_other_users_project(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create(['name' => 'Original']);

        Sanctum::actingAs($user);

        $this->putJson("/api/projects/{$project->id}", [
            'name' => 'Attempted Update',
            'description' => 'Should not work',
        ])
            ->assertForbidden();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Original',
        ]);
    }
}
