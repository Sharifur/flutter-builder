<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Advanced Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">
                    {{ config('app.name') }}
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Advanced Laravel Admin Panel with Meta Information Management
                </p>
                <div class="flex justify-center">
                    <div class="bg-white rounded-lg shadow-lg p-2">
                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiByeD0iMTAiIGZpbGw9IiMzQjgyRjYiLz4KPHN2ZyB4PSIxMiIgeT0iMTIiIHdpZHRoPSIyNiIgaGVpZ2h0PSIyNiI+CjxwYXRoIGQ9Ik0yMS41IDEyLjVMMTcuNSAxNi41TDE5LjUgMTguNUwyNSAxM0wyNi41IDE0LjVMMzAgMTAuNUwyNS41IDZMMjEuNSAxMi41WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cjwvc3ZnPgo=" alt="Admin Panel" class="w-12 h-12">
                    </div>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Advanced Page Management</h3>
                    <p class="text-gray-600 text-sm">Create and manage pages with advanced meta information, SEO optimization, and real-time analysis.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">SEO Analysis & Scoring</h3>
                    <p class="text-gray-600 text-sm">Real-time SEO analysis with scoring system, character counters, and optimization suggestions.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Social Media Previews</h3>
                    <p class="text-gray-600 text-sm">Live previews for Google search results, Facebook Open Graph, and Twitter cards.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Multi-Guard Authentication</h3>
                    <p class="text-gray-600 text-sm">Separate admin and user authentication systems with role-based access control.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Modern UI Components</h3>
                    <p class="text-gray-600 text-sm">Shadcn-inspired Blade components with Tailwind CSS for beautiful, responsive design.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Performance Optimized</h3>
                    <p class="text-gray-600 text-sm">Built with Laravel 12, optimized queries, and efficient data relationships.</p>
                </div>
            </div>

            <!-- Action Section -->
            <div class="text-center">
                <div class="bg-white rounded-xl shadow-lg p-8 inline-block">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
                    <p class="text-gray-600 mb-6">Access the admin panel to manage your content with advanced meta information tools.</p>
                    
                    <div class="space-y-4">
                        <a href="{{ route('admin.login') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Access Admin Panel
                        </a>
                        
                        <div class="text-sm text-gray-500">
                            <p>Demo Credentials:</p>
                            <p class="font-mono bg-gray-100 p-2 rounded mt-1">
                                Email: admin@example.com<br>
                                Password: password
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tech Stack -->
            <div class="mt-16 text-center">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Built With</h3>
                <div class="flex justify-center items-center space-x-8 text-gray-500">
                    <div class="flex items-center">
                        <span class="font-semibold">Laravel 12</span>
                    </div>
                    <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                    <div class="flex items-center">
                        <span class="font-semibold">Tailwind CSS</span>
                    </div>
                    <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                    <div class="flex items-center">
                        <span class="font-semibold">Alpine.js</span>
                    </div>
                    <div class="w-1 h-1 bg-gray-400 rounded-full"></div>
                    <div class="flex items-center">
                        <span class="font-semibold">SQLite</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>