<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function createProject(Request $request)
    {
        $validatedData = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'status'      => ['nullable', 'in:planning,in_progress,completed,cancelled'],
            'description' => ['nullable', 'string'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $project = Project::create([
            'name'        => $validatedData['name'],
            'status'      => $validatedData['status'],
            'description' => $validatedData['description'] ?? null,
            'start_date'  => $validatedData['start_date'] ?? null,
            'end_date'    => $validatedData['end_date'] ?? null,

            // The authenticated user creates the project
            // 'created_by'  => auth()->id(),
            'created_by'  => $request->created_by,

        ]);

        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project,
        ], 201);
    }

}