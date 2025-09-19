@extends('user.layouts.app', ['title' => 'Preview - ' . $project->name])

@section('content')
<div class="h-screen flex flex-col bg-gray-100">
    <!-- Preview Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('user.projects.show', $project) }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Project
                </a>
                <div class="text-sm">
                    <span class="text-gray-500">Previewing:</span>
                    <span class="font-medium text-gray-900">{{ $project->name }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('user.projects.builder', $project) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                    </svg>
                    Edit in Builder
                </a>
                <button onclick="refreshPreview()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Preview Content -->
    <div class="flex-1 flex">
        <!-- Left Sidebar - Pages Navigation -->
        @if($project->appPages->count() > 1)
        <div class="w-64 bg-white border-r border-gray-200 overflow-y-auto">
            <div class="p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Pages</h3>
                <div class="space-y-2">
                    @foreach($project->appPages as $page)
                    <button onclick="navigateToPage('{{ $page->slug }}')"
                            class="page-nav-button w-full text-left px-3 py-2 text-sm rounded-md {{ $loop->first ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}"
                            data-page-slug="{{ $page->slug }}">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ $page->name }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1 ml-6">
                            {{ $page->widgets->count() }} {{ Str::plural('widget', $page->widgets->count()) }}
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Preview Frame -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Loading Indicator -->
                <div id="preview-loading" class="text-center py-8">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-700">Loading preview...</span>
                    </div>
                </div>

                <!-- Preview iframe -->
                <iframe id="preview-frame"
                        src="{{ route('user.projects.preview.iframe', $project) }}"
                        class="hidden w-full border-0 rounded-lg shadow-2xl"
                        style="height: 667px; background: #000;">
                </iframe>

                <!-- Error State -->
                <div id="preview-error" class="hidden text-center py-8">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-red-800">Preview Error</h3>
                        <p class="mt-1 text-sm text-red-600">Failed to load the preview. Please try again.</p>
                        <button onclick="refreshPreview()" class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                            Try Again
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar - Info -->
        <div class="w-80 bg-white border-l border-gray-200 overflow-y-auto">
            <div class="p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Preview Info</h3>

                <div class="space-y-4">
                    <div>
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide">Project</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $project->name }}</p>
                        @if($project->description)
                        <p class="mt-1 text-xs text-gray-500">{{ $project->description }}</p>
                        @endif
                    </div>

                    <div>
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide">Statistics</h4>
                        <div class="mt-2 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Pages:</span>
                                <span class="text-gray-900">{{ $project->appPages->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Widgets:</span>
                                <span class="text-gray-900">{{ $project->appPages->sum(fn($page) => $page->widgets->count()) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Last Updated:</span>
                                <span class="text-gray-900">{{ $project->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    @if($project->appPages->count() > 0)
                    <div>
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide">Current Page</h4>
                        <div class="mt-2" id="current-page-info">
                            <p class="text-sm text-gray-900">{{ $project->appPages->first()->name }}</p>
                            <p class="text-xs text-gray-500">{{ $project->appPages->first()->widgets->count() }} widgets</p>
                        </div>
                    </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Actions</h4>
                        <div class="space-y-2">
                            <button onclick="exportProject()" class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Export Project
                            </button>
                            <button onclick="window.open('{{ route('user.projects.preview.iframe', $project) }}', '_blank')" class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Open in New Tab
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPageSlug = '{{ $project->appPages->first()->slug ?? '' }}';

document.addEventListener('DOMContentLoaded', function() {
    setupPreview();
});

function setupPreview() {
    const iframe = document.getElementById('preview-frame');
    const loading = document.getElementById('preview-loading');
    const error = document.getElementById('preview-error');

    iframe.onload = function() {
        loading.classList.add('hidden');
        error.classList.add('hidden');
        iframe.classList.remove('hidden');
    };

    iframe.onerror = function() {
        loading.classList.add('hidden');
        iframe.classList.add('hidden');
        error.classList.remove('hidden');
    };

    // Fallback timeout
    setTimeout(() => {
        if (loading && !loading.classList.contains('hidden')) {
            iframe.onload();
        }
    }, 5000);
}

function refreshPreview() {
    const iframe = document.getElementById('preview-frame');
    const loading = document.getElementById('preview-loading');
    const error = document.getElementById('preview-error');

    // Show loading state
    iframe.classList.add('hidden');
    error.classList.add('hidden');
    loading.classList.remove('hidden');

    // Force reload iframe
    iframe.src = iframe.src + '?refresh=' + Date.now();
}

function navigateToPage(pageSlug) {
    currentPageSlug = pageSlug;

    // Update page navigation
    document.querySelectorAll('.page-nav-button').forEach(btn => {
        btn.classList.remove('bg-indigo-100', 'text-indigo-700');
        btn.classList.add('text-gray-700', 'hover:bg-gray-100');
    });

    const activeBtn = document.querySelector(`[data-page-slug="${pageSlug}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('text-gray-700', 'hover:bg-gray-100');
        activeBtn.classList.add('bg-indigo-100', 'text-indigo-700');
    }

    // Send message to iframe to navigate
    const iframe = document.getElementById('preview-frame');
    if (iframe && iframe.contentWindow) {
        iframe.contentWindow.postMessage({
            type: 'navigateToPage',
            pageSlug: pageSlug
        }, '*');
    }
}

function exportProject() {
    const downloadUrl = `/api/projects/{{ $project->id }}/export`;
    window.open(downloadUrl, '_blank');
}

// Listen for messages from iframe
window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'pageChanged') {
        // Update current page info
        const pageInfo = document.getElementById('current-page-info');
        if (pageInfo && event.data.page) {
            pageInfo.innerHTML = `
                <p class="text-sm text-gray-900">${event.data.page.name}</p>
                <p class="text-xs text-gray-500">${event.data.page.widgetCount || 0} widgets</p>
            `;
        }
    }
});
</script>
@endsection