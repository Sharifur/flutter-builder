@extends('user.layouts.app', ['title' => 'Edit ' . $dataCollection->name . ' - ' . $project->name])

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
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
                    <span>Edit</span>
                </div>

                <h1 class="text-2xl font-bold text-gray-900">Edit Data Collection</h1>
                <p class="text-gray-600">Update the details of your data collection.</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('user.projects.data-collections.update', [$project, $dataCollection]) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Collection Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $dataCollection->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-300 @enderror" placeholder="e.g., Blog Posts, Products, Users">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">A descriptive name for your data collection.</p>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror" placeholder="Describe what this collection will store...">{{ old('description', $dataCollection->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Optional. Describe the purpose of this data collection.</p>
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700">Icon (FontAwesome)</label>
                        <div class="mt-1 relative">
                            <input type="text" name="icon" id="icon" value="{{ old('icon', $dataCollection->icon) }}" class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('icon') border-red-300 @enderror" placeholder="database">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-{{ old('icon', $dataCollection->icon) }} text-gray-400" id="icon-preview"></i>
                            </div>
                        </div>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">FontAwesome icon name (without 'fa-' prefix). Examples: database, table, list, users, shopping-cart</p>
                    </div>

                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $dataCollection->is_active) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                        <p class="ml-2 text-sm text-gray-500">Inactive collections are hidden from the API.</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Collection Information</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Collection details:</p>
                                    <ul class="list-disc list-inside mt-1 space-y-1">
                                        <li>Slug: <code class="bg-blue-100 px-1 rounded">{{ $dataCollection->slug }}</code></li>
                                        <li>{{ $dataCollection->allFields->count() }} {{ Str::plural('field', $dataCollection->allFields->count()) }} defined</li>
                                        <li>{{ $dataCollection->records->count() }} {{ Str::plural('record', $dataCollection->records->count()) }} stored</li>
                                        @if($dataCollection->is_system)
                                        <li class="text-yellow-700">⚠️ This is a system collection</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('user.projects.data-collections.show', [$project, $dataCollection]) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Collection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('icon').addEventListener('input', function() {
    const iconPreview = document.getElementById('icon-preview');
    iconPreview.className = 'fas fa-' + this.value + ' text-gray-400';
});
</script>
@endsection