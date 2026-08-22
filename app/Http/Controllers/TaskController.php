<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{

    public function createTask(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return response()->json([
            'message' => 'Task created successfully',
            'task'    => $task,
        ], 201);
    }

    public function getProjectRelatedToTask($id)
    {
        $task = Task::with([
            'project:id,name,created_by',
            'assignedTo:id,name',
            'project.creator:id,name',
        ])->find($id);

        return response()->json([
            'message' => 'A task with its project, assignee, and project creator',
            'data'    => $task,
        ], 200);
    }

}