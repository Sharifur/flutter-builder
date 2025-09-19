<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\AppPage;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    private function ensureUserOwnsProject(Project $project)
    {
        if (auth()->user()->id !== $project->user_id) {
            abort(403, 'Unauthorized access to this project.');
        }
    }

    public function index()
    {
        $projects = Auth::user()->projects()->with('appPages')->latest()->get();

        return view('user.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('user.projects.create');
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

        return redirect()->route('user.projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $project->load(['appPages.widgets']);

        if (request()->expectsJson()) {
            return response()->json([
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name,
                    'description' => $project->description,
                    'created_at' => $project->created_at,
                    'updated_at' => $project->updated_at,
                    'pages' => $project->appPages->map(function ($page) {
                        return [
                            'id' => $page->id,
                            'name' => $page->name,
                            'slug' => $page->slug,
                            'widgets' => $page->widgets->map(function ($widget) {
                                return [
                                    'id' => $widget->id,
                                    'type' => $widget->type,
                                    'config' => $widget->config,
                                    'sort_order' => $widget->sort_order,
                                ];
                            }),
                        ];
                    }),
                ]
            ]);
        }

        return view('user.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        return view('user.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $project->update($request->only(['name', 'description']));

        return redirect()->route('user.projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $project->delete();

        return redirect()->route('user.projects.index')
            ->with('success', 'Project deleted successfully!');
    }

    public function builder(Request $request, Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $project->load(['appPages.widgets']);

        // Get the selected page ID from query parameter
        $selectedPageId = $request->get('page');
        $selectedPage = null;

        if ($selectedPageId) {
            $selectedPage = $project->appPages()->find($selectedPageId);
        }

        // If no valid selected page, use the first page
        if (!$selectedPage && $project->appPages->count() > 0) {
            $selectedPage = $project->appPages->first();
        }

        return view('user.projects.builder-react', compact('project', 'selectedPage'));
    }
}