@extends('admin.layouts.admin')

@section('title', 'Pages Management')

@section('breadcrumbs')
    <ol class="flex items-center space-x-2">
        <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
        <li class="text-gray-500">/</li>
        <li class="text-gray-900 font-medium">Pages</li>
    </ol>
@endsection

@section('page-header')
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-900">Pages Management</h2>
        <x-admin.button href="{{ route('admin.pages.create') }}" variant="primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create New Page
        </x-admin.button>
    </div>
@endsection

@section('content')
    <x-admin.card>
        <!-- Filters -->
        <div class="mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-4 items-start sm:items-end">
                <div class="flex-1 min-w-0">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <x-admin.input 
                        type="text" 
                        id="search"
                        name="search" 
                        placeholder="Search pages..."
                        value="{{ request('search') }}"
                    />
                </div>
                
                <div class="w-full sm:w-auto sm:min-w-[120px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <x-admin.select id="status" name="status">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </x-admin.select>
                </div>
                
                <div class="flex gap-2 w-full sm:w-auto">
                    <x-admin.button type="submit" class="flex-1 sm:flex-none">Filter</x-admin.button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.pages.index') }}" class="flex-1 sm:flex-none">
                            <x-admin.button variant="secondary" class="w-full">Clear</x-admin.button>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($pages->count() > 0)
            <!-- Pages Table -->
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SEO</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pages as $page)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $page->title }}</div>
                                        <div class="text-sm text-gray-500">/{{ $page->slug }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $page->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($page->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($page->metaInformation)
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                            <span class="text-xs text-gray-500">Optimized</span>
                                        </div>
                                    @else
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></div>
                                            <span class="text-xs text-gray-500">Basic</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $page->creator->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $page->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <x-admin.button href="{{ route('admin.pages.show', $page) }}" variant="outline" size="sm">
                                            View
                                        </x-admin.button>
                                        <x-admin.button href="{{ route('admin.pages.edit', $page) }}" variant="primary" size="sm">
                                            Edit
                                        </x-admin.button>
                                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this page?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-admin.button type="submit" variant="danger" size="sm">
                                                Delete
                                            </x-admin.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pages->hasPages())
                <div class="mt-6">
                    {{ $pages->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No pages found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'status']))
                        Try adjusting your search criteria or <a href="{{ route('admin.pages.index') }}" class="text-blue-600 hover:text-blue-500">clear filters</a>.
                    @else
                        Get started by creating your first page.
                    @endif
                </p>
                @if(!request()->hasAny(['search', 'status']))
                    <div class="mt-6">
                        <x-admin.button href="{{ route('admin.pages.create') }}" variant="primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Your First Page
                        </x-admin.button>
                    </div>
                @endif
            </div>
        @endif
    </x-admin.card>
@endsection