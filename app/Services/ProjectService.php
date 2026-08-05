<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class ProjectService
{
    public function authorizeProject(int $projectId, string $ability = 'view'): Project
    {
        $project = Project::findOrFail($projectId);
        Gate::authorize($ability, $project);

        return $project;
    }
}
