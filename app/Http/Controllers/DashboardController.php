<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Http\Resources\DashboardResource;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = [
            'total_projects' => Project::count(),
            'active_projects' => Project::where('status', ProjectStatus::ACTIVE)->count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', TaskStatus::DONE)->count(),
            'pending_tasks' => Task::where('status', TaskStatus::TODO)->count(),
            'overdue_tasks' => Task::where('due_date', '<', Carbon::now()->toDateString())->count(),
        ];

        return new DashboardResource($data);
    }
}
