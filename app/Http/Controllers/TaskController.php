<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Traits\ApiResponse;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;
    use ApiResponse;

    public function createTask(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $task = Task::create($request->validated());

        // return $this->successResponse('Task created successfully', 201, $task);
        return $this->successResponse(
            'Task created successfully',
            201,
            new TaskResource($task)
        );

    }

    public function getProjectRelatedToTask($id)
    {
        $task = Task::with([
            'project:id,name,created_by',
            'assignedTo:id,name',
            'project.creator:id,name',
        ])->findOrFail($id);

        return $this->successResponse(
            'A task with its project, assignee, and project creator',
            200,
            new TaskResource($task)
        );
    }

    public function getAllTasks()
    {

        $this->authorize('viewAny', Task::class);

        $tasks = Task::with([
            'project:id,name,created_by',
            'assignedTo:id,name',
            'project.creator:id,name',
        ])->get();

        return $this->successResponse(
            $tasks->isEmpty() ? 'No tasks found ..' : "All tasks with their project, assignee, and project creator",
            200,
            TaskResource::collection($tasks)
        );
    }

    public function getTask($id)
    {

        $task = Task::with([
            'project:id,name,created_by',
            'assignedTo:id,name',
            'project.creator:id,name',
        ])->findOrFail($id);

        $this->authorize('view', $task);

        return $this->successResponse(
            $task ? 'Task found with its project, assignee, and project creator' : 'Task not found',
            200,
            new TaskResource($task)
        );
    }
}