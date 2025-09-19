@extends('admin.layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
            <p class="text-gray-600">View user account information</p>
        </div>
        <div class="space-x-2">
            <x-admin.button variant="secondary" onclick="window.history.back()">
                Back
            </x-admin.button>
            <a href="{{ route('admin.users.edit', $user) }}">
                <x-admin.button>Edit User</x-admin.button>
            </a>
        </div>
    </div>

    <x-admin.card>
        <x-slot name="header">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 rounded-full bg-green-500 flex items-center justify-center text-white text-xl font-medium">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </x-slot>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Email Status</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $user->email_verified_at ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $user->email_verified_at ? 'Verified' : 'Pending Verification' }}
                    </span>
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y g:i A') }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('M d, Y g:i A') }}</dd>
            </div>

            @if($user->email_verified_at)
            <div>
                <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->email_verified_at->format('M d, Y g:i A') }}</dd>
            </div>
            @endif
        </div>
    </x-admin.card>
</div>
@endsection