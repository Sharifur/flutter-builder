@extends('frontend.layout')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    @if($page->show_breadcrumb)
        <!-- Breadcrumbs -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li>
                    <a href="/" class="hover:text-gray-700">Home</a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li class="text-gray-900">{{ $page->title }}</li>
            </ol>
        </nav>
    @endif

    <!-- Page Header -->
    <header class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
        <div class="text-sm text-gray-500">
            Published {{ $page->created_at->format('F j, Y') }}
            @if($page->updated_at->notEqualTo($page->created_at))
                • Updated {{ $page->updated_at->format('F j, Y') }}
            @endif
        </div>
    </header>

    <!-- Page Content -->
    <article class="prose prose-lg max-w-none">
        {!! $page->content !!}
    </article>

    <!-- Page Meta Info (for debugging - remove in production) -->
    @if(config('app.debug') && $page->metaInformation)
        <div class="mt-16 p-6 bg-gray-100 rounded-lg">
            <h3 class="text-lg font-semibold mb-4">SEO Information (Debug Mode)</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <strong>Meta Title:</strong> {{ $page->metaInformation->meta_title }}
                </div>
                <div>
                    <strong>Meta Description:</strong> {{ $page->metaInformation->meta_description }}
                </div>
                <div>
                    <strong>Focus Keyword:</strong> {{ $page->metaInformation->focus_keyword }}
                </div>
                <div>
                    <strong>SEO Score:</strong> {{ $page->metaInformation->seo_score }}/100
                </div>
            </div>
        </div>
    @endif
</div>
@endsection