@extends('admin.layouts.admin')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Breadcrumbs -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-500">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">Dashboard</a>
            </li>
            <li>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900">Edit Profile</li>
        </ol>
    </nav>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
        <p class="text-gray-600">Update your personal information and account settings</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <x-admin.alert type="success" class="mb-6">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    <x-admin.card>
        <x-slot name="header">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-medium">
                    {{ substr($admin->name, 0, 2) }}
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $admin->name }}</h3>
                    <p class="text-gray-500">{{ $admin->email }}</p>
                    <p class="text-sm text-blue-600 capitalize">{{ $admin->role }} Administrator</p>
                </div>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
            @csrf

            <x-admin.form-group>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                <x-admin.input 
                    type="text" 
                    id="name" 
                    name="name"
                    required 
                    placeholder="Enter your full name"
                    value="{{ old('name', $admin->name) }}"
                />
                @error('name')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </x-admin.form-group>

            <x-admin.form-group>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                <x-admin.input 
                    type="email" 
                    id="email" 
                    name="email"
                    required 
                    placeholder="Enter your email address"
                    value="{{ old('email', $admin->email) }}"
                />
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                <p class="mt-1 text-sm text-gray-500">This email will be used for login and notifications.</p>
            </x-admin.form-group>

            <!-- Read-only Information -->
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-sm font-medium text-gray-900 mb-4">Account Information</h4>
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
                        <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $admin->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $admin->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $admin->created_at->format('M d, Y') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $admin->updated_at->format('M d, Y g:i A') }}</dd>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.dashboard') }}">
                    <x-admin.button variant="secondary">
                        Cancel
                    </x-admin.button>
                </a>
                <x-admin.button type="submit">
                    Update Profile
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>

    <!-- Quick Actions -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('admin.profile.change-password') }}" class="flex-1">
            <x-admin.card class="cursor-pointer hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Change Password</h3>
                        <p class="text-sm text-gray-500">Update your account security</p>
                    </div>
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </x-admin.card>
        </a>
    </div>
</div>
@endsection