<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function createTask(Request $request)
    {
        $validatedData = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],

            'status'      => [
                'required',
                'in:pending,in_progress,completed,cancelled',
            ],

            'priority'    => [
                'required',
                'in:low,medium,high',
            ],

            'assigned_to' => [
                'nullable',
                'exists:users,id',
            ],

            'project_id'  => [
                'required',
                'exists:projects,id',
            ],

            'due_date'    => [
                'nullable',
                'date',
            ],
        ]);

        $task = Task::create($validatedData);

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
