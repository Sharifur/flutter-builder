@extends('admin.layouts.admin')

@section('title', 'Change Password')

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
            <li>
                <a href="{{ route('admin.profile.edit') }}" class="hover:text-gray-700">Profile</a>
            </li>
            <li>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-gray-900">Change Password</li>
        </ol>
    </nav>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Change Password</h1>
        <p class="text-gray-600">Update your account password for enhanced security</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <x-admin.alert type="success" class="mb-6">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    <x-admin.card>
        <x-slot name="header">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Password Security</h3>
                    <p class="text-sm text-gray-500">Ensure your account is secure with a strong password</p>
                </div>
            </div>
        </x-slot>

        <form method="POST" action="{{ route('admin.profile.update-password') }}" class="space-y-6">
            @csrf

            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Password Requirements</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>At least 8 characters long</li>
                                <li>Mix of uppercase and lowercase letters recommended</li>
                                <li>Include numbers and special characters for better security</li>
                                <li>Avoid using personal information</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <x-admin.form-group>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password *</label>
                <x-admin.input 
                    type="password" 
                    id="current_password" 
                    name="current_password"
                    required 
                    placeholder="Enter your current password"
                    autocomplete="current-password"
                />
                @error('current_password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                <p class="mt-1 text-sm text-gray-500">For security, please confirm your current password.</p>
            </x-admin.form-group>

            <x-admin.form-group>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password *</label>
                <x-admin.input 
                    type="password" 
                    id="password" 
                    name="password"
                    required 
                    placeholder="Enter a strong new password"
                    autocomplete="new-password"
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
                    placeholder="Confirm your new password"
                    autocomplete="new-password"
                />
                <p class="mt-1 text-sm text-gray-500">Please re-enter your new password to confirm.</p>
            </x-admin.form-group>

            <div class="border-t border-gray-200 pt-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Security Notice</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>After changing your password, you may be logged out of other devices. This is a security measure to protect your account.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.profile.edit') }}">
                    <x-admin.button variant="secondary">
                        Cancel
                    </x-admin.button>
                </a>
                <x-admin.button type="submit">
                    Update Password
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>

    <!-- Quick Actions -->
    <div class="mt-6">
        <a href="{{ route('admin.profile.edit') }}">
            <x-admin.card class="cursor-pointer hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Edit Profile</h3>
                        <p class="text-sm text-gray-500">Update your personal information</p>
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