@extends('admin.layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create New User</h1>
        <p class="text-gray-600">Add a new user account</p>
    </div>

    <x-admin.card>
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
            @csrf

            <x-admin.form-group>
                <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                <x-admin.input 
                    type="text" 
                    id="name" 
                    name="name"
                    required 
                    placeholder="Full name"
                    value="{{ old('name') }}"
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
                    placeholder="user@example.com"
                    value="{{ old('email') }}"
                />
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </x-admin.form-group>

            <x-admin.form-group>
                <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
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
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                <x-admin.input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation"
                    required 
                    placeholder="Confirm password"
                />
            </x-admin.form-group>

            <x-admin.form-group>
                <label class="flex items-center space-x-2">
                    <input 
                        type="checkbox" 
                        name="is_active"
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
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
                    Create User
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection