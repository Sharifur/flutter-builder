@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <x-admin.card class="text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_pages'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Pages</div>
        </x-admin.card>
        
        <x-admin.card class="text-center">
            <div class="text-3xl font-bold text-green-600">{{ $stats['published_pages'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Published</div>
        </x-admin.card>
        
        <x-admin.card class="text-center">
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['draft_pages'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Drafts</div>
        </x-admin.card>
        
        <x-admin.card class="text-center">
            <div class="text-3xl font-bold text-purple-600">{{ $stats['total_admins'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Admins</div>
        </x-admin.card>
        
        <x-admin.card class="text-center">
            <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_users'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Users</div>
        </x-admin.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Pages -->
        <x-admin.card title="Recent Pages">
            @if($recent_pages->count() > 0)
                <div class="space-y-4">
                    @foreach($recent_pages as $page)
                        <div class="flex items-center justify-between border-b border-gray-200 pb-3">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $page->title }}</h4>
                                <p class="text-xs text-gray-500">
                                    Created by {{ $page->creator->name }} • {{ $page->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $page->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($page->status) }}
                                </span>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 text-center">
                    <x-admin.button href="{{ route('admin.pages.index') }}" variant="outline" size="sm">
                        View All Pages
                    </x-admin.button>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No pages yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first page.</p>
                    <div class="mt-6">
                        <x-admin.button href="{{ route('admin.pages.create') }}" variant="primary" size="sm">
                            Create Page
                        </x-admin.button>
                    </div>
                </div>
            @endif
        </x-admin.card>

        <!-- Quick Actions -->
        <x-admin.card title="Quick Actions">
            <div class="space-y-4">
                <x-admin.button href="{{ route('admin.pages.create') }}" variant="primary" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create New Page
                </x-admin.button>
                
                <x-admin.button href="{{ route('admin.pages.index') }}" variant="outline" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Manage Pages
                </x-admin.button>
                
                <hr class="my-4">
                
                <div class="text-sm text-gray-600 space-y-2">
                    <h4 class="font-medium">System Status</h4>
                    <div class="flex items-center justify-between">
                        <span>Laravel Version</span>
                        <span class="text-green-600">{{ app()->version() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>PHP Version</span>
                        <span class="text-green-600">{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>
@endsection