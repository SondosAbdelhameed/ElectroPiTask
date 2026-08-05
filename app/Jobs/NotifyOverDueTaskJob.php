<?php

namespace App\Jobs;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class NotifyOverDueTaskJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $users = Task::with('project.user')
                ->where('due_date', '<', now())
                ->where('status', '!=', TaskStatus::DONE)
                ->get()
                ->pluck('project.user')
                ->filter()
                ->unique('id')
                ->values();

            // $users now contains distinct users with overdue tasks.
            foreach ($users as $user) {
                // dispatch notification for each unique user here
                // e.g. SendOverdueTaskNotification::dispatch($user, ...);
                $user->notify(new TaskOverdueNotification(
                    Task::with('project.user')
                        ->where('due_date', '<', now())
                        ->where('status', '!=', TaskStatus::DONE)
                        ->whereHas('project.user', fn ($query) => $query->where('id', $user->id))
                        ->get()
                ));

            }
        }catch (\Exception $e) {
            // Log the exception or handle it as needed
            Log::error('Error in NotifyOverDueTaskJob: ' . $e->getMessage());
        }
    }
}
