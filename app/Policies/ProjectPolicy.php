<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function view(User $user, Project $project): Response
    {
        if(!($user->id === $project->user_id)){
            return Response::deny('You do not own this project.');
        }
        return Response::allow();
    }

    public function update(User $user, Project $project): Response
    {
        if(!($user->id === $project->user_id)){
            return Response::deny('You do not own this project.');
        }
        return Response::allow();
    }

    public function delete(User $user, Project $project): Response
    {
        if(!($user->id === $project->user_id)){
            return Response::deny('You do not own this project.');
        }
        return Response::allow();
    }
}
