<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title', 'description', 'project_id', 'status', 'priority', 'due_date'])]
/** @use HasFactory<TaskFactory> */
class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'status' => TaskStatus::class,
        ];
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('status')) {
            $query = $query->where('status', $request->input('status'));
        }

        if ($request->has('priority')) {
            $query = $query->where('priority', $request->input('priority'));
        }

        if ($request->has('search')) {
            $query = $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        return $query;

    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
