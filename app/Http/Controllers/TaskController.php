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

    public function getProjectsRelatedToTask($id)
    {
        $task    = Task::find($id);
        $project = $task->project;

        return response()->json([
            'message' => 'اتفضل يعم البروجيكت بتاعك',
            'data'    => $project,
        ], 200);
    }

}
