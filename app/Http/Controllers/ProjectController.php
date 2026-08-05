<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\User\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::where('user_id', auth()->id())->paginate();
        return ProjectResource::collection($projects);
        
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
    public function store(ProjectRequest $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        return new SuccessResource(Response::HTTP_OK,"Project Created Successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ProjectService $projectService)
    {
        $project = $projectService->authorizeProject($id);

        return new ProjectResource($project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, string $id, ProjectService $projectService)
    {
        $project = $projectService->authorizeProject($id, 'update');

        $project->update($request->validated());
        return new SuccessResource(Response::HTTP_OK,"Project Updated Successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, ProjectService $projectService)
    {
        $project = $projectService->authorizeProject($id, 'delete');

        $project->delete();
        return new SuccessResource(Response::HTTP_OK,"Project deleted successfully");
    }
}
