@extends('user.layouts.app', ['title' => $dataCollection->name . ' - ' . $project->name])

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
                    <span>{{ $dataCollection->name }}</span>
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
                            <div class="flex items-center">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $dataCollection->name }}</h1>
                                @if($dataCollection->is_system)
                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        System Collection
                                    </span>
                                @endif
                                @if(!$dataCollection->is_active)
                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            @if($dataCollection->description)
                            <p class="text-gray-600 mt-1">{{ $dataCollection->description }}</p>
                            @endif
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mt-2">
                                <span>{{ $dataCollection->allFields->count() }} {{ Str::plural('field', $dataCollection->allFields->count()) }}</span>
                                <span>{{ $dataCollection->records->count() }} {{ Str::plural('record', $dataCollection->records->count()) }}</span>
                                <span>Slug: {{ $dataCollection->slug }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('user.projects.data-collections.records', [$project, $dataCollection]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2z"></path>
                            </svg>
                            View Data
                        </a>
                        <a href="{{ route('user.projects.data-collections.api', [$project, $dataCollection]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            API Info
                        </a>
                        @if(!$dataCollection->is_system)
                        <a href="{{ route('user.projects.data-collections.edit', [$project, $dataCollection]) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Fields Management -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Fields List -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Collection Fields</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage the structure of your data collection.</p>
                    </div>

                    @if($dataCollection->allFields->count() > 0)
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($dataCollection->allFields as $field)
                        <li class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded bg-gray-100 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600">
                                                @switch($field->type)
                                                    @case('text')
                                                        T
                                                        @break
                                                    @case('number')
                                                        #
                                                        @break
                                                    @case('boolean')
                                                        ✓
                                                        @break
                                                    @case('date')
                                                        📅
                                                        @break
                                                    @case('email')
                                                        @
                                                        @break
                                                    @default
                                                        ?
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center space-x-2">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $field->label }}</h4>
                                            <span class="text-xs text-gray-500 font-mono">{{ $field->name }}</span>
                                            @if($field->is_required)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Required</span>
                                            @endif
                                            @if($field->is_unique)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Unique</span>
                                            @endif
                                            @if(!$field->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500 mt-1">
                                            <span>Type: {{ ucfirst($field->type) }}</span>
                                            @if($field->default_value)
                                                <span>Default: {{ $field->default_value }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="editField({{ $field->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</button>
                                    <form method="POST" action="{{ route('user.projects.data-collections.fields.destroy', [$project, $dataCollection, $field]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this field?')" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No fields</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding your first field to this collection.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Add Field Form -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Add Field</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Add a new field to this collection.</p>
                    </div>

                    <div class="px-4 py-5">
                        <form method="POST" action="{{ route('user.projects.data-collections.fields.store', [$project, $dataCollection]) }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Field Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror" placeholder="field_name">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Must be unique, lowercase, no spaces.</p>
                            </div>

                            <div>
                                <label for="label" class="block text-sm font-medium text-gray-700">Field Label</label>
                                <input type="text" name="label" id="label" value="{{ old('label') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('label') border-red-300 @enderror" placeholder="Display Name">
                                @error('label')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Field Type</label>
                                <select name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('type') border-red-300 @enderror">
                                    @foreach($fieldTypes as $type => $label)
                                        <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="default_value" class="block text-sm font-medium text-gray-700">Default Value</label>
                                <input type="text" name="default_value" id="default_value" value="{{ old('default_value') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Optional">
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input id="is_required" name="is_required" type="checkbox" value="1" {{ old('is_required') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_required" class="ml-2 block text-sm text-gray-900">Required field</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="is_unique" name="is_unique" type="checkbox" value="1" {{ old('is_unique') ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_unique" class="ml-2 block text-sm text-gray-900">Unique values only</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="is_searchable" name="is_searchable" type="checkbox" value="1" {{ old('is_searchable', true) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_searchable" class="ml-2 block text-sm text-gray-900">Searchable</label>
                                </div>
                            </div>

                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Add Field
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Records -->
        @if($dataCollection->records->count() > 0)
        <div class="mt-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Records</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Latest {{ $dataCollection->records->count() }} records in this collection.</p>
                    </div>
                    <a href="{{ route('user.projects.data-collections.records', [$project, $dataCollection]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View all records →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                @foreach($dataCollection->allFields->take(5) as $field)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $field->label }}</th>
                                @endforeach
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($dataCollection->records as $record)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $record->id }}</td>
                                @foreach($dataCollection->allFields->take(5) as $field)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @php
                                        $fieldData = $record->data->where('field_id', $field->id)->first();
                                    @endphp
                                    {{ $fieldData ? Str::limit($fieldData->field_value, 50) : '-' }}
                                </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function editField(fieldId) {
    // TODO: Implement field editing modal or redirect to edit page
    alert('Field editing will be implemented in the next update.');
}
</script>
@endsection