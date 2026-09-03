<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

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
        $project = Project::with([
            'tasks:id,title,assigned_to,project_id',
            'tasks.assignedTo:id,name',
        ])->findOrFail($id);

        // You don't pass here the Project class, you pass the project
        // instance that you want to check if the user can view it or not
        $this->authorize('view', $project);

        return response()->json([
            'message' => 'Here your project...',
            'project' => $project,
        ], 200);
    }

    // getAllProjects
    public function getAllProjects()
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::all();

        return response()->json([
            'message'  => $projects->isEmpty() ? 'No projects found ..' : "All projects fetched successfully ..",
            'projects' => $projects,
        ], 200);
    }
}
