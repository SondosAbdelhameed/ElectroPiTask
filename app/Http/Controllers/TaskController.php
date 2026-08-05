<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\TaskResource;
use App\Models\Task;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $project, Request $request, ProjectService $projectService)
    {
        $projectModel = $projectService->authorizeProject($project);

        $tasks = Task::where('project_id', $projectModel->id)->filter($request)->paginate();
        return TaskResource::collection($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request, int $project, ProjectService $projectService)
    {
        $projectModel = $projectService->authorizeProject($project);

        $task = Task::create(array_merge($request->all(), ['project_id' => $projectModel->id]));
        return new SuccessResource(Response::HTTP_OK,"Task Created Successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(int $project, int $task, ProjectService $projectService)
    {
        $projectModel = $projectService->authorizeProject($project);

        $taskModel = Task::find($task);
        if (!$taskModel) {
            return new ErrorResource(Response::HTTP_NOT_FOUND, "Task not found.");
        }

        Gate::authorize('view', [$taskModel, $projectModel->id]);

        return new TaskResource($taskModel);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, int $project, int $task, ProjectService $projectService)
    {
        $projectModel = $projectService->authorizeProject($project, 'update');

        $taskModel = Task::find($task);
        if (!$taskModel) {
            return new ErrorResource(Response::HTTP_NOT_FOUND, "Task not found.");
        }

        Gate::authorize('update', [$taskModel, $projectModel->id]);

        $taskModel->update($request->all());
        return new SuccessResource(Response::HTTP_OK,"Task updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $project, int $task, ProjectService $projectService)
    {
        $projectModel = $projectService->authorizeProject($project, 'delete');

        $taskModel = Task::find($task);
        if (!$taskModel) {
            return new ErrorResource(Response::HTTP_NOT_FOUND, "Task not found.");
        }

        Gate::authorize('delete', [$taskModel, $projectModel->id]);

        $taskModel->delete();
        return new SuccessResource(Response::HTTP_OK,"Task deleted successfully");
    }
}
