<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function view(User $user, Task $task, int $projectId): Response
    {
        if ($task->project_id !== $projectId) {
            return Response::deny('Task does not belong to this project.');
        }

        if (!($user->id === $task->project->user_id)) {
            return Response::deny('You do not own this task.');
        }

        return Response::allow();
    }

    public function update(User $user, Task $task, int $projectId): Response
    {
        if ($task->project_id !== $projectId) {
            return Response::deny('Task does not belong to this project.');
        }

        if (!($user->id === $task->project->user_id)) {
            return Response::deny('You do not own this task.');
        }

        return Response::allow();
    }

    public function delete(User $user, Task $task, int $projectId): Response
    {
        if ($task->project_id !== $projectId) {
            return Response::deny('Task does not belong to this project.');
        }

        if (!($user->id === $task->project->user_id)) {
            return Response::deny('You do not own this task.');
        }

        return Response::allow();
    }
}
