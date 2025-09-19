<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\DataCollection;
use App\Models\CollectionField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DataCollectionController extends Controller
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

        $collections = $project->dataCollections()
            ->with(['allFields'])
            ->orderBy('sort_order')
            ->paginate(10);

        if (request()->expectsJson()) {
            return response()->json([
                'collections' => $collections->items(),
                'pagination' => [
                    'current_page' => $collections->currentPage(),
                    'total' => $collections->total(),
                    'per_page' => $collections->perPage(),
                    'last_page' => $collections->lastPage(),
                ]
            ]);
        }

        return view('user.projects.data-collections.index', compact('project', 'collections'));
    }

    public function create(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        return view('user.projects.data-collections.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
        ]);

        $collection = $project->dataCollections()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'icon' => $validated['icon'] ?? 'database',
            'is_system' => false,
            'is_active' => true,
            'sort_order' => $project->dataCollections()->max('sort_order') + 1,
        ]);

        return redirect()
            ->route('user.projects.data-collections.show', [$project, $collection])
            ->with('success', 'Data collection created successfully!');
    }

    public function show(Project $project, DataCollection $dataCollection)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);

        $dataCollection->load(['allFields', 'records' => function ($query) {
            $query->latest()->limit(10);
        }]);

        $fieldTypes = CollectionField::FIELD_TYPES;

        if (request()->expectsJson()) {
            return response()->json([
                'collection' => $dataCollection,
                'fields' => $dataCollection->allFields,
                'records' => $dataCollection->records,
                'field_types' => $fieldTypes
            ]);
        }

        return view('user.projects.data-collections.show', compact(
            'project',
            'dataCollection',
            'fieldTypes'
        ));
    }

    public function edit(Project $project, DataCollection $dataCollection)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);

        return view('user.projects.data-collections.edit', compact('project', 'dataCollection'));
    }

    public function update(Request $request, Project $project, DataCollection $dataCollection)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $dataCollection->update($validated);

        return redirect()
            ->route('user.projects.data-collections.show', [$project, $dataCollection])
            ->with('success', 'Data collection updated successfully!');
    }

    public function destroy(Project $project, DataCollection $dataCollection)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);

        if ($dataCollection->is_system) {
            return back()->with('error', 'Cannot delete system collections.');
        }

        $dataCollection->delete();

        return redirect()
            ->route('user.projects.data-collections.index', $project)
            ->with('success', 'Data collection deleted successfully!');
    }

    public function addField(Request $request, Project $project, DataCollection $dataCollection)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z_][a-zA-Z0-9_]*$/',
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(CollectionField::FIELD_TYPES)),
            'default_value' => 'nullable|string',
            'is_required' => 'boolean',
            'is_unique' => 'boolean',
            'is_searchable' => 'boolean',
            'validation_rules' => 'nullable|array',
            'field_options' => 'nullable|array',
            'related_collection_id' => 'nullable|exists:data_collections,id',
        ]);

        // Check if field name already exists in this collection
        if ($dataCollection->allFields()->where('name', $validated['name'])->exists()) {
            return back()->withErrors(['name' => 'Field name already exists in this collection.']);
        }

        $field = $dataCollection->allFields()->create([
            'name' => $validated['name'],
            'label' => $validated['label'],
            'type' => $validated['type'],
            'default_value' => $validated['default_value'],
            'is_required' => $validated['is_required'] ?? false,
            'is_unique' => $validated['is_unique'] ?? false,
            'is_searchable' => $validated['is_searchable'] ?? true,
            'validation_rules' => $validated['validation_rules'] ?? [],
            'field_options' => $validated['field_options'] ?? [],
            'related_collection_id' => $validated['related_collection_id'] ?? null,
            'sort_order' => $dataCollection->allFields()->max('sort_order') + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Field added successfully!');
    }

    public function updateField(Request $request, Project $project, DataCollection $dataCollection, CollectionField $field)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);
        $this->ensureFieldBelongsToCollection($field, $dataCollection);

        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'default_value' => 'nullable|string',
            'is_required' => 'boolean',
            'is_unique' => 'boolean',
            'is_searchable' => 'boolean',
            'validation_rules' => 'nullable|array',
            'field_options' => 'nullable|array',
            'related_collection_id' => 'nullable|exists:data_collections,id',
            'is_active' => 'boolean',
        ]);

        $field->update($validated);

        return back()->with('success', 'Field updated successfully!');
    }

    public function deleteField(Project $project, DataCollection $dataCollection, CollectionField $field)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);
        $this->ensureFieldBelongsToCollection($field, $dataCollection);

        $field->delete();

        return back()->with('success', 'Field deleted successfully!');
    }

    public function getFields(DataCollection $dataCollection)
    {
        $this->ensureCollectionBelongsToProject($dataCollection, $dataCollection->project);
        $this->ensureUserOwnsProject($dataCollection->project);

        $fields = $dataCollection->allFields;

        return response()->json([
            'fields' => $fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'label' => $field->label,
                    'type' => $field->type,
                    'required' => $field->required,
                    'options' => $field->options,
                ];
            })
        ]);
    }

    public function records(Project $project, DataCollection $dataCollection)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);

        $records = $dataCollection->records()
            ->with(['data.field'])
            ->latest()
            ->paginate(20);

        return view('user.projects.data-collections.records', compact(
            'project',
            'dataCollection',
            'records'
        ));
    }

    public function apiInfo(Project $project, DataCollection $dataCollection)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);

        $endpoints = [
            'list' => [
                'method' => 'GET',
                'url' => url("/api/projects/{$project->id}/collections/{$dataCollection->slug}"),
                'description' => 'Get all records in this collection',
            ],
            'create' => [
                'method' => 'POST',
                'url' => url("/api/projects/{$project->id}/collections/{$dataCollection->slug}"),
                'description' => 'Create a new record',
            ],
            'show' => [
                'method' => 'GET',
                'url' => url("/api/projects/{$project->id}/collections/{$dataCollection->slug}/{record_id}"),
                'description' => 'Get a specific record',
            ],
            'update' => [
                'method' => 'PUT',
                'url' => url("/api/projects/{$project->id}/collections/{$dataCollection->slug}/{record_id}"),
                'description' => 'Update a specific record',
            ],
            'delete' => [
                'method' => 'DELETE',
                'url' => url("/api/projects/{$project->id}/collections/{$dataCollection->slug}/{record_id}"),
                'description' => 'Delete a specific record',
            ],
        ];

        return view('user.projects.data-collections.api', compact(
            'project',
            'dataCollection',
            'endpoints'
        ));
    }

    private function ensureCollectionBelongsToProject(DataCollection $collection, Project $project)
    {
        if ($collection->project_id !== $project->id) {
            abort(404);
        }
    }

    private function ensureFieldBelongsToCollection(CollectionField $field, DataCollection $collection)
    {
        if ($field->collection_id !== $collection->id) {
            abort(404);
        }
    }

    public function react(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        return view('user.projects.data-collections.react', compact('project'));
    }

    public function getCollectionsForMapping(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $collections = $project->dataCollections()
            ->active()
            ->with(['allFields' => function ($query) {
                $query->active()->orderBy('sort_order');
            }])
            ->orderBy('name')
            ->get()
            ->map(function ($collection) {
                return [
                    'id' => $collection->id,
                    'name' => $collection->name,
                    'slug' => $collection->slug,
                    'fields' => $collection->allFields->map(function ($field) {
                        return [
                            'id' => $field->id,
                            'name' => $field->name,
                            'label' => $field->label,
                            'type' => $field->type,
                            'is_required' => $field->is_required,
                        ];
                    })
                ];
            });

        return response()->json($collections);
    }

    public function getUserData(Project $project, DataCollection $dataCollection, Request $request)
    {
        $this->ensureUserOwnsProject($project);
        $this->ensureCollectionBelongsToProject($dataCollection, $project);

        $user = auth()->user();
        $userId = $request->input('user_id', $user->id);

        // Get user's records from this collection
        $records = $dataCollection->records()
            ->where('created_by', $userId)
            ->with(['data.field'])
            ->latest()
            ->take(10)
            ->get();

        $formattedRecords = $records->map(function ($record) {
            $data = [];
            foreach ($record->data as $dataItem) {
                $data[$dataItem->field->name] = $dataItem->field->castValue($dataItem->field_value);
            }
            return [
                'id' => $record->id,
                'data' => $data,
                'created_at' => $record->created_at
            ];
        });

        return response()->json([
            'records' => $formattedRecords,
            'user_id' => $userId
        ]);
    }

    public function getRelatedCollections(Project $project)
    {
        $this->ensureUserOwnsProject($project);

        $collections = $project->dataCollections()
            ->active()
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json($collections);
    }
}