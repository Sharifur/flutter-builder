<div class="flex flex-col h-full">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-6 bg-blue-600 text-white">
        <h1 class="text-xl font-bold">{{ config('app.name') }}</h1>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <x-admin.sidebar-link 
            :href="route('admin.dashboard')" 
            :active="request()->routeIs('admin.dashboard')"
            icon="home">
            Dashboard
        </x-admin.sidebar-link>
        
        <!-- Pages Management -->
        <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2">
                Content
            </div>
            
            <x-admin.sidebar-link 
                :href="route('admin.pages.index')" 
                :active="request()->routeIs('admin.pages.*')"
                icon="document-text">
                Pages
            </x-admin.sidebar-link>
        </div>
        
        <!-- Users Management -->
        <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2">
                Users
            </div>
            
            <x-admin.sidebar-link 
                :href="route('admin.users.index')" 
                :active="request()->routeIs('admin.users.*')"
                icon="users">
                Users
            </x-admin.sidebar-link>
            
            <x-admin.sidebar-link 
                :href="route('admin.admins.index')" 
                :active="request()->routeIs('admin.admins.*')"
                icon="shield-check">
                Admins
            </x-admin.sidebar-link>
        </div>
        
        <!-- Settings (Future) -->
        <div class="space-y-1">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2">
                Settings
            </div>
            
            <x-admin.sidebar-link 
                :href="'#'" 
                :active="false"
                icon="cog">
                General Settings
            </x-admin.sidebar-link>
        </div>
    </nav>
    
    <!-- User Info -->
    @auth('admin')
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">
                    {{ substr(auth('admin')->user()->name, 0, 1) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ auth('admin')->user()->name }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ auth('admin')->user()->email }}
                </p>
            </div>
        </div>
    </div>
    @endauth
</div>