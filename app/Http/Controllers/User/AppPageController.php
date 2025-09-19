<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\AppPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppPageController extends Controller
{
    use AuthorizesRequests;

    private function ensureUserOwnsProject(Project $project)
    {
        if (auth()->user()->id !== $project->user_id) {
            abort(403, 'Unauthorized access to this project.');
        }
    }

    public function index(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $pages = $project->appPages()->with('widgets')->get();

        return view('user.pages.index', compact('project', 'pages'));
    }

    public function create(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        return view('user.pages.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|alpha_dash',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);

        // Check if slug is unique within the project
        if ($project->appPages()->where('slug', $slug)->exists()) {
            return back()->withErrors(['slug' => 'Page slug must be unique within the project.']);
        }

        $page = $project->appPages()->create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Page created successfully!',
                'page' => $page
            ]);
        }

        return redirect()->route('user.projects.pages.show', [$project, $page])
            ->with('success', 'Page created successfully!');
    }

    public function show(Project $project, AppPage $page)
    {
        $this->ensureUserOwnsProject($project);

        $page->load('widgets');

        return view('user.pages.show', compact('project', 'page'));
    }

    public function edit(Project $project, AppPage $page)
    {
        $this->ensureUserOwnsProject($project);

        return view('user.pages.edit', compact('project', 'page'));
    }

    public function update(Request $request, Project $project, AppPage $page)
    {
        $this->ensureUserOwnsProject($project);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash',
        ]);

        // Check if slug is unique within the project (excluding current page)
        if ($project->appPages()->where('slug', $request->slug)->where('id', '!=', $page->id)->exists()) {
            return back()->withErrors(['slug' => 'Page slug must be unique within the project.']);
        }

        $page->update($request->only(['name', 'slug']));

        return redirect()->route('user.projects.pages.show', [$project, $page])
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Project $project, AppPage $page)
    {
        $this->ensureUserOwnsProject($project);

        // Don't allow deletion if it's the only page
        if ($project->appPages()->count() <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last page in the project.']);
        }

        $page->delete();

        return redirect()->route('user.projects.pages.index', $project)
            ->with('success', 'Page deleted successfully!');
    }
}