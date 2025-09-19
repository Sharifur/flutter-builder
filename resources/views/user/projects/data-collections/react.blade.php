@extends('user.layouts.sidebar', [
    'title' => 'Data Collections - ' . $project->name,
    'project' => $project,
    'pageTitle' => 'Data Collections',
    'breadcrumbs' => [
        ['title' => 'Projects', 'url' => route('user.projects.index')],
        ['title' => $project->name, 'url' => route('user.projects.show', $project)],
        ['title' => 'Data Collections', 'url' => null]
    ]
])

@section('content')
<div class="p-6">
    <!-- React App Container -->
        <div
            id="react-data-collections"
            data-project-id="{{ $project->id }}"
            data-project-name="{{ $project->name }}"
            data-csrf-token="{{ csrf_token() }}"
        >
            <!-- Loading state while React initializes -->
            <div class="flex items-center justify-center h-64">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            </div>
        </div>
</div>

<!-- Include FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@vite(['resources/js/react-app.jsx'])
@endsection