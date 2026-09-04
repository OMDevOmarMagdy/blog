<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Traits\ApiResponse;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    use ApiResponse;

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

        return $this->successResponse('Project created successfully', 201, $project);
    }

    public function getTasksRelatedToProject($id)
    {
        $project = Project::findOrFail($id);
        $tasks   = $project->tasks;

        return $this->successResponse('Tasks related to project fetched successfully', 200, $tasks);
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

        return $this->successResponse('Project fetched successfully', 200, $project);
    }

    // getAllProjects
    public function getAllProjects()
    {
        $this->authorize('viewAny', Project::class);

        $projects = Project::all();

        return $this->successResponse(
            $projects->isEmpty() ? 'No projects found ..' : "All projects fetched successfully ..",
            200,
            $projects
        );
    }
}
