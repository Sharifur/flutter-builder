@extends('admin.layouts.admin')

@section('title', 'Admin Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Admin Management</h1>
            <p class="text-gray-600">Manage administrator accounts and permissions</p>
        </div>
        <a href="{{ route('admin.admins.create') }}">
            <x-admin.button>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Admin
            </x-admin.button>
        </a>
    </div>

    <!-- Filters -->
    <x-admin.card>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-admin.form-group>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <x-admin.input 
                    type="text" 
                    id="search" 
                    name="search" 
                    placeholder="Name or email"
                    value="{{ request('search') }}"
                />
            </x-admin.form-group>

            <x-admin.form-group>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <x-admin.select id="status" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </x-admin.select>
            </x-admin.form-group>

            <x-admin.form-group>
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <x-admin.select id="role" name="role">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="editor" {{ request('role') == 'editor' ? 'selected' : '' }}>Editor</option>
                </x-admin.select>
            </x-admin.form-group>

            <div class="flex items-end space-x-2">
                <x-admin.button type="submit">Filter</x-admin.button>
                <x-admin.button variant="secondary" type="button" onclick="window.location.href='{{ route('admin.admins.index') }}'">
                    Clear
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>

    <!-- Admins Table -->
    <x-admin.card>
        <x-admin.table>
            <x-slot name="header">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </x-slot>

            @forelse($admins as $admin)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                <div class="text-sm text-gray-500">{{ $admin->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($admin->role === 'admin') bg-purple-100 text-purple-800
                            @elseif($admin->role === 'manager') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($admin->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $admin->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $admin->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $admin->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.admins.show', $admin) }}" 
                           class="text-blue-600 hover:text-blue-900"
                           title="View Details">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.admins.edit', $admin) }}" 
                           class="text-indigo-600 hover:text-indigo-900"
                           title="Edit Admin">
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        @if($admin->id !== auth('admin')->id())
                            <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        title="Delete Admin">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        No admins found.
                    </td>
                </tr>
            @endforelse
        </x-admin.table>

        @if($admins->hasPages())
            <div class="px-6 py-3">
                {{ $admins->links() }}
            </div>
        @endif
    </x-admin.card>
</div>
@endsection