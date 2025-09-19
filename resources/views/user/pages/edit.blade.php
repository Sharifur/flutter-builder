@extends('user.layouts.sidebar', [
    'title' => 'Edit ' . $page->name . ' - ' . $project->name,
    'project' => $project,
    'pageTitle' => 'Edit ' . $page->name,
    'breadcrumbs' => [
        ['title' => 'Projects', 'url' => route('user.projects.index')],
        ['title' => $project->name, 'url' => route('user.projects.show', $project)],
        ['title' => 'Pages', 'url' => route('user.projects.pages.index', $project)],
        ['title' => $page->name, 'url' => route('user.projects.pages.show', [$project, $page])],
        ['title' => 'Edit', 'url' => null]
    ]
])

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('user.projects.pages.update', [$project, $page]) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Page Name
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name', $page->name) }}"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                               placeholder="e.g., Home, Profile, Settings"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug
                        </label>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old('slug', $page->slug) }}"
                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('slug') border-red-300 @enderror"
                               placeholder="e.g., home, profile, settings"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Only letters, numbers, hyphens, and underscores allowed.</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('user.projects.pages.show', [$project, $page]) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Page
                            </button>
                        </div>

                        @if($project->appPages()->count() > 1)
                        <form method="POST" action="{{ route('user.projects.pages.destroy', [$project, $page]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this page? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Page
                            </button>
                        </form>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($page->widgets->count() > 0)
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Page Widgets</h3>
                <div class="bg-gray-50 rounded-md p-4">
                    <p class="text-sm text-gray-600 mb-2">
                        This page contains <strong>{{ $page->widgets->count() }}</strong> widget(s).
                        Deleting this page will also remove all its widgets.
                    </p>
                    <a href="{{ route('user.projects.builder', $project) }}?page={{ $page->id }}"
                       class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                        Manage widgets in Builder →
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const originalSlug = slugInput.value;

    nameInput.addEventListener('input', function() {
        // Only auto-generate slug if it hasn't been manually changed
        if (slugInput.value === originalSlug || slugInput.dataset.autoGenerated) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    slugInput.addEventListener('input', function() {
        if (this.value !== originalSlug) {
            delete this.dataset.autoGenerated;
        }
    });
});
</script>
@endsection