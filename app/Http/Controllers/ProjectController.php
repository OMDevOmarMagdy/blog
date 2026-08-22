<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    public function createProject(StoreProjectRequest $request)
    {
        $data = $request->validated();

        $project = Project::create([
            'name'        => $data['name'],
            'status'      => $data['status'],
            'description' => $data['description'] ?? null,
            'start_date'  => $data['start_date'] ?? null,
            'end_date'    => $data['end_date'] ?? null,

            // The authenticated user creates the project
            'created_by'  => auth()->id(),
            // 'created_by'  => $request->created_by,
        ]);

        return response()->json([
            'message' => 'Project created successfully',
            'project' => $project,
        ], 201);
    }

    public function getTasksRelatedToProject($id)
    {
        $project = Project::findOrFail($id);
        $tasks   = $project->tasks;

        return response()->json([
            'message' => 'اتفضل يعم التاسكات بتاعتك',
            'data'    => $tasks,
        ], 200);
    }

    // getProject
    public function getProject($id)
    {
        // get the project with the id and name of the creator
        // $project = Project::with('creator:id,name')->find($id);

        // You replece id with id and name
        // $project->created_by = [
        //     'id'   => $project->creator->id,
        //     'name' => $project->creator->name,
        // ];

        // Don't print the creator field in the response
        // unset($project->creator);

        $project = Project::with([
            'tasks:id,title,assigned_to,project_id',
            'tasks.assignedTo:id,name',
        ])->findOrFail($id);

        // $project = Project::with([
        //     'tasks',
        //     'tasks.assignedTo',
        // ])->find($id);

        return response()->json([
            'message' => 'Here your project...',
            'project' => $project,
        ], 200);
    }

    // getAllProjects
    public function getAllProjects()
    {
        $projects = Project::all();

        return response()->json([
            'message'  => $projects->isEmpty() ? 'No projects found ..' : "All projects fetched successfully ..",
            'projects' => $projects,
        ], 200);
    }
}
