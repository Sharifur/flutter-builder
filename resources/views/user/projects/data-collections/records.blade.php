@extends('user.layouts.app', ['title' => $dataCollection->name . ' Records - ' . $project->name])

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
                    <span>Records</span>
                </div>

                <div class="flex justify-between items-start">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center">
                                @if($dataCollection->icon)
                                    <i class="fas fa-{{ $dataCollection->icon }} text-green-600 text-xl"></i>
                                @else
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $dataCollection->name }} Records</h1>
                            <p class="text-gray-600">{{ $records->total() }} {{ Str::plural('record', $records->total()) }} in this collection</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.projects.data-collections.show', [$project, $dataCollection]) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Collection
                        </a>
                        <a href="{{ route('user.projects.data-collections.api', [$project, $dataCollection]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            API Info
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($records->count() > 0)
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UUID</th>
                            @foreach($dataCollection->allFields as $field)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $field->label }}
                                @if($field->is_required)
                                    <span class="text-red-500">*</span>
                                @endif
                            </th>
                            @endforeach
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $record->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                {{ Str::limit($record->record_uuid, 8) }}...
                            </td>
                            @foreach($dataCollection->allFields as $field)
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @php
                                    $fieldData = $record->data->where('field_id', $field->id)->first();
                                    $value = $fieldData ? $fieldData->field_value : '';
                                @endphp

                                @if($field->type === 'boolean')
                                    @if($value === '1' || $value === 'true')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Yes
                                        </span>
                                    @elseif($value === '0' || $value === 'false')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            No
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                @elseif($field->type === 'date')
                                    @if($value)
                                        {{ \Carbon\Carbon::parse($value)->format('M j, Y') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                @elseif($field->type === 'datetime')
                                    @if($value)
                                        {{ \Carbon\Carbon::parse($value)->format('M j, Y g:i A') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                @elseif($field->type === 'email')
                                    @if($value)
                                        <a href="mailto:{{ $value }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ Str::limit($value, 30) }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                @elseif($field->type === 'url')
                                    @if($value)
                                        <a href="{{ $value }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                            {{ Str::limit($value, 30) }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                @elseif($field->type === 'json')
                                    @if($value)
                                        <details class="cursor-pointer">
                                            <summary class="text-indigo-600 hover:text-indigo-900">JSON</summary>
                                            <pre class="mt-2 text-xs bg-gray-100 p-2 rounded">{{ json_encode(json_decode($value), JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                @else
                                    @if($value)
                                        <div class="max-w-xs">{{ Str::limit($value, 50) }}</div>
                                        @if(strlen($value) > 50)
                                            <span class="text-xs text-gray-400" title="{{ $value }}">...</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                @endif
                            </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $record->created_at->format('M j, Y') }}
                                <div class="text-xs text-gray-400">{{ $record->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $record->updated_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $records->links() }}
            </div>
            @endif
        </div>
        @else
        <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No records</h3>
                    <p class="mt-1 text-sm text-gray-500">This collection doesn't have any data yet. Records will appear here when data is added through the API.</p>
                    <div class="mt-6">
                        <a href="{{ route('user.projects.data-collections.api', [$project, $dataCollection]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            View API Documentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Collection Summary -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Collection Summary</h3>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Records</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $records->total() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Fields</dt>
                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $dataCollection->allFields->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Collection Slug</dt>
                        <dd class="mt-1 text-lg font-mono text-gray-900">{{ $dataCollection->slug }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection