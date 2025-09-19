<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $projects = Auth::user()->projects()
            ->with(['appPages.widgets'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $projects->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                    'pages_count' => $project->appPages->count(),
                    'widgets_count' => $project->appPages->sum(fn($page) => $page->widgets->count()),
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $project = Auth::user()->projects()->create($request->only(['name', 'description']));

        // Create a default home page
        $project->appPages()->create([
            'name' => 'Home',
            'slug' => 'home',
        ]);

        $project->load(['appPages.widgets']);

        return response()->json([
            'success' => true,
            'data' => $project,
            'message' => 'Project created successfully!',
        ], 201);
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load(['appPages.widgets']);

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $project->update($request->only(['name', 'description']));

        return response()->json([
            'success' => true,
            'data' => $project,
            'message' => 'Project updated successfully!',
        ]);
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully!',
        ]);
    }
}