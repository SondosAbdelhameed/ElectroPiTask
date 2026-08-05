<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_is_allowed_to_manage_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user)->create();
        $policy = new ProjectPolicy();

        $this->assertTrue($policy->view($user, $project)->allowed());
        $this->assertTrue($policy->update($user, $project)->allowed());
        $this->assertTrue($policy->delete($user, $project)->allowed());
    }

    public function test_non_owner_is_denied_access_to_project(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $project = Project::factory()->for($owner)->create();
        $policy = new ProjectPolicy();

        $this->assertTrue($policy->view($user, $project)->denied());
        $this->assertTrue($policy->update($user, $project)->denied());
        $this->assertTrue($policy->delete($user, $project)->denied());
    }
}
