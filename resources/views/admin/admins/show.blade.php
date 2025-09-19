@extends('admin.layouts.admin')

@section('title', 'Admin Details')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Details</h1>
            <p class="text-gray-600">View admin account information</p>
        </div>
        <div class="space-x-2">
            <x-admin.button variant="secondary" onclick="window.history.back()">
                Back
            </x-admin.button>
            <a href="{{ route('admin.admins.edit', $admin) }}">
                <x-admin.button>Edit Admin</x-admin.button>
            </a>
        </div>
    </div>

    <x-admin.card>
        <x-slot name="header">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-medium">
                    {{ substr($admin->name, 0, 2) }}
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $admin->name }}</h3>
                    <p class="text-gray-500">{{ $admin->email }}</p>
                </div>
            </div>
        </x-slot>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Role</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($admin->role === 'admin') bg-purple-100 text-purple-800
                        @elseif($admin->role === 'manager') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($admin->role) }}
                    </span>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $admin->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $admin->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Pages Created</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $admin->createdPages()->count() }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Pages Updated</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $admin->updatedPages()->count() }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $admin->created_at->format('M d, Y g:i A') }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $admin->updated_at->format('M d, Y g:i A') }}</dd>
            </div>
        </div>
    </x-admin.card>

    @if($admin->createdPages()->count() > 0)
        <x-admin.card class="mt-6">
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900">Recent Pages Created</h3>
            </x-slot>

            <div class="space-y-3">
                @foreach($admin->createdPages()->latest()->limit(5)->get() as $page)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                        <div>
                            <a href="{{ route('admin.pages.show', $page) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                {{ $page->title }}
                            </a>
                            <p class="text-sm text-gray-500">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    {{ $page->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($page->status) }}
                                </span>
                                • Created {{ $page->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-admin.card>
    @endif
</div>
@endsection