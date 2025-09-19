@props(['type' => 'google', 'title' => '', 'description' => '', 'url' => '', 'image' => ''])

<div class="bg-white rounded-lg border p-4">
    <h4 class="text-sm font-medium text-gray-900 mb-3 capitalize">{{ $type }} Preview</h4>
    
    @if($type === 'google')
        <!-- Google Search Result Preview -->
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-sm">
                <span class="text-green-700">{{ parse_url($url ?: 'https://example.com', PHP_URL_HOST) ?: 'example.com' }}</span>
                <span class="text-gray-500">›</span>
                <span class="text-gray-500">{{ Str::limit(parse_url($url ?: 'https://example.com/page-slug', PHP_URL_PATH) ?: '/page-slug', 30) }}</span>
            </div>
            <h3 class="text-blue-600 text-xl hover:underline cursor-pointer">
                {{ $title ?: 'Page Title - Site Name' }}
            </h3>
            <p class="text-gray-600 text-sm leading-relaxed">
                {{ $description ?: 'This is where your meta description will appear in search results. Make it compelling to encourage clicks.' }}
            </p>
        </div>
        
    @elseif($type === 'facebook')
        <!-- Facebook/Open Graph Preview -->
        <div class="border rounded-lg overflow-hidden max-w-md">
            @if($image)
                <img src="{{ $image }}" alt="" class="w-full h-40 object-cover bg-gray-200">
            @else
                <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            @endif
            <div class="p-3 bg-gray-50">
                <p class="text-xs text-gray-500 uppercase mb-1">{{ parse_url($url ?: 'https://example.com', PHP_URL_HOST) ?: 'EXAMPLE.COM' }}</p>
                <h3 class="font-semibold text-gray-900 text-sm mb-1">
                    {{ $title ?: 'Page Title' }}
                </h3>
                <p class="text-gray-600 text-xs">
                    {{ Str::limit($description ?: 'This is your Open Graph description that appears when shared on Facebook.', 100) }}
                </p>
            </div>
        </div>
        
    @elseif($type === 'twitter')
        <!-- Twitter Card Preview -->
        <div class="border rounded-xl overflow-hidden max-w-md">
            @if($image)
                <img src="{{ $image }}" alt="" class="w-full h-48 object-cover bg-gray-200">
            @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            @endif
            <div class="p-3">
                <h3 class="font-semibold text-gray-900 text-sm mb-1">
                    {{ $title ?: 'Page Title' }}
                </h3>
                <p class="text-gray-600 text-sm mb-2">
                    {{ Str::limit($description ?: 'This is your Twitter card description.', 120) }}
                </p>
                <p class="text-gray-500 text-xs">{{ parse_url($url ?: 'https://example.com', PHP_URL_HOST) ?: 'example.com' }}</p>
            </div>
        </div>
    @endif
</div>