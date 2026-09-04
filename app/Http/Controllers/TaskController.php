<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
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

        return $this->successResponse('Task created successfully', 201, $task);
    }

    public function getProjectRelatedToTask($id)
    {
        $task = Task::with([
            'project:id,name,created_by',
            'assignedTo:id,name',
            'project.creator:id,name',
        ])->find($id);

        return $this->successResponse('A task with its project, assignee, and project creator', 200, $task);
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
            $tasks
        );
    }

    public function getTask($id)
    {

        $task = Task::with([
            'project:id,name,created_by',
            'assignedTo:id,name',
            'project.creator:id,name',
        ])->find($id);

        $this->authorize('view', $task);

        return $this->successResponse('A task with its project, assignee, and project creator', 200, $task);
    }
}
