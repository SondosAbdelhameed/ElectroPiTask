<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_is_allowed_to_manage_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($project)->create();
        $policy = new TaskPolicy();

        $this->assertTrue($policy->view($user, $task, $project->id)->allowed());
        $this->assertTrue($policy->update($user, $task, $project->id)->allowed());
        $this->assertTrue($policy->delete($user, $task, $project->id)->allowed());
    }

    public function test_request_with_wrong_project_id_is_denied(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $otherProject = Project::factory()->for($user)->create();
        $task = Task::factory()->for($otherProject)->create();
        $policy = new TaskPolicy();

        $this->assertTrue($policy->view($user, $task, $project->id)->denied());
        $this->assertTrue($policy->update($user, $task, $project->id)->denied());
        $this->assertTrue($policy->delete($user, $task, $project->id)->denied());
    }

    public function test_non_owner_is_denied_access_to_task(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $task = Task::factory()->for($project)->create();
        $policy = new TaskPolicy();

        $this->assertTrue($policy->view($user, $task, $project->id)->denied());
        $this->assertTrue($policy->update($user, $task, $project->id)->denied());
        $this->assertTrue($policy->delete($user, $task, $project->id)->denied());
    }
}
