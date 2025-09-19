@extends('user.layouts.app', ['title' => $dataCollection->name . ' API - ' . $project->name])

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('user.projects.index') }}" class="hover:text-gray-700">Projects</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('user.projects.show', $project) }}" class="hover:text-gray-700">{{ $project->name }}</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('user.projects.data-collections.index', $project) }}" class="hover:text-gray-700">Data Collections</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('user.projects.data-collections.show', [$project, $dataCollection]) }}" class="hover:text-gray-700">{{ $dataCollection->name }}</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span>API Documentation</span>
                </div>

                <div class="flex justify-between items-start">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $dataCollection->name }} API</h1>
                            <p class="text-gray-600">REST API endpoints for your {{ $dataCollection->name }} collection</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.projects.data-collections.show', [$project, $dataCollection]) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Collection
                        </a>
                        <a href="{{ route('user.projects.data-collections.records', [$project, $dataCollection]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z"></path>
                            </svg>
                            View Records
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Overview -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">API Overview</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Base URL</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-900">{{ url('/api') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Collection Slug</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-900">{{ $dataCollection->slug }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Content Type</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-900">application/json</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Authentication</dt>
                            <dd class="mt-1 text-sm font-mono text-gray-900">API Key (Coming Soon)</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Endpoints -->
        <div class="mt-8 space-y-6">
            @foreach($endpoints as $name => $endpoint)
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($endpoint['method'] === 'GET') bg-green-100 text-green-800
                                @elseif($endpoint['method'] === 'POST') bg-blue-100 text-blue-800
                                @elseif($endpoint['method'] === 'PUT') bg-yellow-100 text-yellow-800
                                @elseif($endpoint['method'] === 'DELETE') bg-red-100 text-red-800
                                @endif">
                                {{ $endpoint['method'] }}
                            </span>
                            <h4 class="text-lg font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $name) }}</h4>
                        </div>
                        <button onclick="copyToClipboard('{{ $endpoint['url'] }}')" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Copy URL
                        </button>
                    </div>

                    <p class="text-gray-600 mb-4">{{ $endpoint['description'] }}</p>

                    <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                        <code class="text-green-400 text-sm">{{ $endpoint['method'] }}</code>
                        <code class="text-white text-sm ml-2">{{ $endpoint['url'] }}</code>
                    </div>

                    @if($name === 'create' || $name === 'update')
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-900 mb-2">Request Body Example:</h5>
                        <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-green-400 text-sm">{
@foreach($dataCollection->allFields as $field)
  "{{ $field->name }}": @if($field->type === 'text' || $field->type === 'email' || $field->type === 'url')
"{{ $field->default_value ?: 'example value' }}"
@elseif($field->type === 'number')
{{ $field->default_value ?: '123' }}
@elseif($field->type === 'boolean')
{{ $field->default_value ?: 'true' }}
@elseif($field->type === 'date')
"{{ $field->default_value ?: '2024-01-01' }}"
@elseif($field->type === 'datetime')
"{{ $field->default_value ?: '2024-01-01 12:00:00' }}"
@elseif($field->type === 'json')
{{ $field->default_value ?: '{}' }}
@else
"{{ $field->default_value ?: 'example' }}"
@endif
@if(!$loop->last),
@endif
@endforeach
}</pre>
                        </div>
                    </div>
                    @endif

                    @if($name === 'list')
                    <div class="mt-4">
                        <h5 class="text-sm font-medium text-gray-900 mb-2">Response Example:</h5>
                        <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-green-400 text-sm">{
  "data": [
    {
      "id": 1,
      "uuid": "550e8400-e29b-41d4-a716-446655440000",
@foreach($dataCollection->allFields->take(3) as $field)
      "{{ $field->name }}": @if($field->type === 'text' || $field->type === 'email' || $field->type === 'url')"example value"@elseif($field->type === 'number')123@elseif($field->type === 'boolean')true@elseif($field->type === 'date')"2024-01-01"@elseif($field->type === 'datetime')"2024-01-01T12:00:00Z"@elseif($field->type === 'json'){}@else"example"@endif,
@endforeach
      "created_at": "2024-01-01T12:00:00Z",
      "updated_at": "2024-01-01T12:00:00Z"
    }
  ],
  "meta": {
    "total": 1,
    "per_page": 20,
    "current_page": 1
  }
}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Field Reference -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Field Reference</h3>
                <p class="text-gray-600 mb-4">Available fields in the {{ $dataCollection->name }} collection:</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Constraints</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Default</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dataCollection->allFields as $field)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">{{ $field->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $field->label }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($field->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex space-x-1">
                                        @if($field->is_required)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Required</span>
                                        @endif
                                        @if($field->is_unique)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Unique</span>
                                        @endif
                                        @if($field->is_searchable)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Searchable</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                                    {{ $field->default_value ?: '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No fields defined yet. <a href="{{ route('user.projects.data-collections.show', [$project, $dataCollection]) }}" class="text-indigo-600 hover:text-indigo-900">Add fields</a> to start using the API.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Usage Notes -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">API Status</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>The API endpoints are automatically generated based on your collection structure. Currently in development:</p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Authentication and API key system</li>
                            <li>Rate limiting and request validation</li>
                            <li>Advanced filtering and search capabilities</li>
                            <li>Webhook notifications for data changes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show a temporary success message
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('text-green-600');
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('text-green-600');
        }, 2000);
    });
}
</script>
@endsection