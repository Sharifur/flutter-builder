<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\AppPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppPageController extends Controller
{
    use AuthorizesRequests;
    public function index(Project $project)
    {
        $this->authorize('view', $project);

        $pages = $project->appPages()->with('widgets')->get();

        return response()->json([
            'success' => true,
            'data' => $pages,
        ]);
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|alpha_dash',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        // Check if slug is unique within the project
        if ($project->appPages()->where('slug', $slug)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Page slug must be unique within the project.',
                'errors' => ['slug' => ['Page slug must be unique within the project.']],
            ], 422);
        }

        $page = $project->appPages()->create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        $page->load('widgets');

        return response()->json([
            'success' => true,
            'data' => $page,
            'message' => 'Page created successfully!',
        ], 201);
    }

    public function show(AppPage $page)
    {
        $this->authorize('view', $page->project);

        $page->load('widgets');

        return response()->json([
            'success' => true,
            'data' => $page,
        ]);
    }

    public function update(Request $request, AppPage $page)
    {
        $this->authorize('update', $page->project);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash',
        ]);

        // Check if slug is unique within the project (excluding current page)
        if ($page->project->appPages()->where('slug', $request->slug)->where('id', '!=', $page->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Page slug must be unique within the project.',
                'errors' => ['slug' => ['Page slug must be unique within the project.']],
            ], 422);
        }

        $page->update($request->only(['name', 'slug']));

        return response()->json([
            'success' => true,
            'data' => $page,
            'message' => 'Page updated successfully!',
        ]);
    }

    public function destroy(AppPage $page)
    {
        $this->authorize('update', $page->project);

        // Don't allow deletion if it's the only page
        if ($page->project->appPages()->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the last page in the project.',
                'errors' => ['error' => ['Cannot delete the last page in the project.']],
            ], 422);
        }

        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully!',
        ]);
    }
}