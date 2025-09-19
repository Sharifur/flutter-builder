<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Dynamic Meta Information -->
    @if(isset($page) && $page->metaInformation)
        <title>{{ $page->metaInformation->meta_title ?: $page->title }}</title>
        <meta name="description" content="{{ $page->metaInformation->meta_description }}">
        <meta name="keywords" content="{{ $page->metaInformation->meta_keywords }}">
        <meta name="robots" content="{{ $page->metaInformation->meta_robots ?: 'index,follow' }}">
        <link rel="canonical" href="{{ $page->metaInformation->canonical_url ?: url()->current() }}">
        
        <!-- Open Graph Tags -->
        <meta property="og:title" content="{{ $page->metaInformation->og_title ?: $page->metaInformation->meta_title ?: $page->title }}">
        <meta property="og:description" content="{{ $page->metaInformation->og_description ?: $page->metaInformation->meta_description }}">
        <meta property="og:url" content="{{ $page->metaInformation->og_url ?: url()->current() }}">
        <meta property="og:type" content="{{ $page->metaInformation->og_type ?: 'website' }}">
        @if($page->metaInformation->og_image)
            <meta property="og:image" content="{{ $page->metaInformation->og_image }}">
        @endif
        <meta property="og:site_name" content="{{ $page->metaInformation->og_site_name ?: config('app.name') }}">
        
        <!-- Twitter Card Tags -->
        <meta name="twitter:card" content="{{ $page->metaInformation->twitter_card ?: 'summary' }}">
        <meta name="twitter:title" content="{{ $page->metaInformation->twitter_title ?: $page->metaInformation->og_title ?: $page->title }}">
        <meta name="twitter:description" content="{{ $page->metaInformation->twitter_description ?: $page->metaInformation->og_description }}">
        @if($page->metaInformation->twitter_image)
            <meta name="twitter:image" content="{{ $page->metaInformation->twitter_image }}">
        @endif
        @if($page->metaInformation->twitter_site)
            <meta name="twitter:site" content="{{ $page->metaInformation->twitter_site }}">
        @endif
        
        <!-- Structured Data -->
        @if($page->metaInformation->schema_markup)
            <script type="application/ld+json">
                {!! $page->metaInformation->schema_markup !!}
            </script>
        @endif
    @else
        <title>@yield('title', config('app.name'))</title>
        <meta name="description" content="@yield('description', 'Welcome to our website')">
    @endif
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('head')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-gray-900">
                        {{ config('app.name') }}
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/admin/login" class="text-gray-600 hover:text-gray-900">
                        Admin Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>