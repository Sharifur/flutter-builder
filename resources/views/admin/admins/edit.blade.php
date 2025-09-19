@extends('admin.layouts.admin')

@section('title', 'Edit Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Admin</h1>
        <p class="text-gray-600">Update admin account information</p>
    </div>

    <x-admin.card>
        <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <x-admin.form-group>
                <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                <x-admin.input 
                    type="text" 
                    id="name" 
                    name="name"
                    required 
                    placeholder="Full name"
                    value="{{ old('name', $admin->name) }}"
                />
                @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </x-admin.form-group>

            <x-admin.form-group>
                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                <x-admin.input 
                    type="email" 
                    id="email" 
                    name="email"
                    required 
                    placeholder="admin@example.com"
                    value="{{ old('email', $admin->email) }}"
                />
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </x-admin.form-group>

            <x-admin.form-group>
                <label for="role" class="block text-sm font-medium text-gray-700">Role *</label>
                <x-admin.select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ old('role', $admin->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="editor" {{ old('role', $admin->role) == 'editor' ? 'selected' : '' }}>Editor</option>
                </x-admin.select>
                @error('role')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </x-admin.form-group>

            <x-admin.form-group>
                <label class="flex items-center space-x-2">
                    <input 
                        type="checkbox" 
                        name="is_active"
                        value="1"
                        {{ old('is_active', $admin->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>
            </x-admin.form-group>

            <div class="flex justify-end space-x-3 pt-4">
                <x-admin.button variant="secondary" type="button" onclick="window.history.back()">
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit">
                    Update Admin
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>

    <!-- Change Password Section -->
    <x-admin.card class="mt-6">
        <x-slot name="header">
            <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
        </x-slot>

        <form method="POST" action="{{ route('admin.admins.change-password', $admin) }}" class="space-y-6">
            @csrf

            @if($admin->id === auth('admin')->id())
                <x-admin.form-group>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password *</label>
                    <x-admin.input 
                        type="password" 
                        id="current_password" 
                        name="current_password"
                        required 
                        placeholder="Enter current password"
                    />
                    @error('current_password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </x-admin.form-group>
            @endif

            <x-admin.form-group>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password *</label>
                <x-admin.input 
                    type="password" 
                    id="password" 
                    name="password"
                    required 
                    placeholder="Minimum 8 characters"
                />
                @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </x-admin.form-group>

            <x-admin.form-group>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password *</label>
                <x-admin.input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation"
                    required 
                    placeholder="Confirm new password"
                />
            </x-admin.form-group>

            <div class="flex justify-end">
                <x-admin.button type="submit" variant="secondary">
                    Change Password
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection