@extends('admin.layouts.admin')

@section('title', 'Edit Page')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Page</h1>
        <p class="text-gray-600">Edit page and meta information</p>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Page Information -->
        <x-admin.card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900">Page Information</h3>
            </x-slot>

            <div class="grid grid-cols-1 gap-6">
                <x-admin.form-group>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                    <x-admin.input 
                        type="text" 
                        id="title" 
                        name="title" 
                        required 
                        placeholder="Enter page title"
                        value="{{ old('title', $page->title) }}"
                    />
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-admin.form-group>

                <x-admin.form-group>
                    <label for="slug" class="block text-sm font-medium text-gray-700">Slug *</label>
                    <div class="relative">
                        <x-admin.input 
                            type="text" 
                            id="slug" 
                            name="slug" 
                            required 
                            placeholder="page-url-slug"
                            value="{{ old('slug', $page->slug) }}"
                            readonly
                            class="pr-10"
                        />
                        <button type="button" id="edit-slug-btn" onclick="toggleSlugEdit()" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    </div>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Click edit icon to modify the slug.</p>
                </x-admin.form-group>

                <x-admin.form-group>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content *</label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="8" 
                        required
                        placeholder="Enter page content"
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    >{{ old('content', $page->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-admin.form-group>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.form-group>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                        <x-admin.select id="status" name="status" required>
                            <option value="">Select status</option>
                            <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published</option>
                        </x-admin.select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-admin.form-group>

                    <x-admin.form-group>
                        <label class="flex items-center space-x-2">
                            <input 
                                type="checkbox" 
                                name="show_breadcrumb" 
                                value="1" 
                                {{ old('show_breadcrumb', $page->show_breadcrumb) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <span class="text-sm font-medium text-gray-700">Show Breadcrumb</span>
                        </label>
                    </x-admin.form-group>
                </div>
            </div>
        </x-admin.card>

        <!-- Meta Information with Tabs -->
        <x-admin.card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900">Meta Information (Optional)</h3>
            </x-slot>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button type="button" 
                            onclick="showTab('basic-seo')" 
                            id="tab-basic-seo"
                            class="tab-button border-b-2 border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 text-sm font-medium">
                        Basic SEO
                    </button>
                    <button type="button" 
                            onclick="showTab('open-graph')" 
                            id="tab-open-graph"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 text-sm font-medium">
                        Open Graph
                    </button>
                    <button type="button" 
                            onclick="showTab('twitter')" 
                            id="tab-twitter"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 text-sm font-medium">
                        Twitter
                    </button>
                    <button type="button" 
                            onclick="showTab('advanced')" 
                            id="tab-advanced"
                            class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 text-sm font-medium">
                        Advanced
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
                <!-- Basic SEO Tab -->
                <div id="content-basic-seo" class="tab-content space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-admin.form-group>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                            <x-admin.input 
                                type="text" 
                                id="meta_title" 
                                name="meta_title" 
                                placeholder="SEO optimized title (50-60 characters)"
                                value="{{ old('meta_title', optional($page->metaInformation)->meta_title) }}"
                            />
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>

                        <x-admin.form-group>
                            <label for="focus_keyword" class="block text-sm font-medium text-gray-700">Focus Keyword</label>
                            <x-admin.input 
                                type="text" 
                                id="focus_keyword" 
                                name="focus_keyword" 
                                placeholder="main target keyword"
                                value="{{ old('focus_keyword', optional($page->metaInformation)->focus_keyword) }}"
                            />
                            @error('focus_keyword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>
                    </div>

                    <x-admin.form-group>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <textarea 
                            id="meta_description" 
                            name="meta_description" 
                            rows="3" 
                            placeholder="SEO optimized description (150-160 characters)"
                            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >{{ old('meta_description', optional($page->metaInformation)->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-admin.form-group>

                    <x-admin.form-group>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                        <x-admin.input 
                            type="text" 
                            id="meta_keywords" 
                            name="meta_keywords" 
                            placeholder="keyword1, keyword2, keyword3"
                            value="{{ old('meta_keywords', optional($page->metaInformation)->meta_keywords) }}"
                        />
                        @error('meta_keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Separate keywords with commas. Recommended: 3-5 keywords.</p>
                    </x-admin.form-group>
                </div>

                <!-- Open Graph Tab -->
                <div id="content-open-graph" class="tab-content space-y-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-admin.form-group>
                            <label for="og_title" class="block text-sm font-medium text-gray-700">Open Graph Title</label>
                            <x-admin.input 
                                type="text" 
                                id="og_title" 
                                name="og_title" 
                                placeholder="Facebook sharing title"
                                value="{{ old('og_title', optional($page->metaInformation)->og_title) }}"
                            />
                            @error('og_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>

                        <x-admin.form-group>
                            <label for="og_image" class="block text-sm font-medium text-gray-700">Open Graph Image URL</label>
                            <x-admin.input 
                                type="url" 
                                id="og_image" 
                                name="og_image" 
                                placeholder="https://example.com/image.jpg"
                                value="{{ old('og_image', optional($page->metaInformation)->og_image) }}"
                            />
                            @error('og_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>
                    </div>

                    <x-admin.form-group>
                        <label for="og_description" class="block text-sm font-medium text-gray-700">Open Graph Description</label>
                        <textarea 
                            id="og_description" 
                            name="og_description" 
                            rows="3" 
                            placeholder="Facebook sharing description"
                            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >{{ old('og_description', optional($page->metaInformation)->og_description) }}</textarea>
                        @error('og_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-admin.form-group>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-admin.form-group>
                            <label for="og_type" class="block text-sm font-medium text-gray-700">Open Graph Type</label>
                            <x-admin.select id="og_type" name="og_type">
                                <option value="website" {{ old('og_type', optional($page->metaInformation)->og_type) == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="article" {{ old('og_type', optional($page->metaInformation)->og_type) == 'article' ? 'selected' : '' }}>Article</option>
                                <option value="product" {{ old('og_type', optional($page->metaInformation)->og_type) == 'product' ? 'selected' : '' }}>Product</option>
                            </x-admin.select>
                            @error('og_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>

                        <x-admin.form-group>
                            <label for="og_url" class="block text-sm font-medium text-gray-700">Open Graph URL</label>
                            <x-admin.input 
                                type="url" 
                                id="og_url" 
                                name="og_url" 
                                placeholder="https://example.com/page"
                                value="{{ old('og_url', optional($page->metaInformation)->og_url) }}"
                            />
                            @error('og_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>
                    </div>

                    <x-admin.form-group>
                        <label for="og_site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                        <x-admin.input 
                            type="text" 
                            id="og_site_name" 
                            name="og_site_name" 
                            placeholder="Your Site Name"
                            value="{{ old('og_site_name', optional($page->metaInformation)->og_site_name) }}"
                        />
                        @error('og_site_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-admin.form-group>
                </div>

                <!-- Twitter Tab -->
                <div id="content-twitter" class="tab-content space-y-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-admin.form-group>
                            <label for="twitter_card" class="block text-sm font-medium text-gray-700">Twitter Card Type</label>
                            <x-admin.select id="twitter_card" name="twitter_card">
                                <option value="summary" {{ old('twitter_card', optional($page->metaInformation)->twitter_card) == 'summary' ? 'selected' : '' }}>Summary</option>
                                <option value="summary_large_image" {{ old('twitter_card', optional($page->metaInformation)->twitter_card) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                                <option value="app" {{ old('twitter_card', optional($page->metaInformation)->twitter_card) == 'app' ? 'selected' : '' }}>App</option>
                                <option value="player" {{ old('twitter_card', optional($page->metaInformation)->twitter_card) == 'player' ? 'selected' : '' }}>Player</option>
                            </x-admin.select>
                            @error('twitter_card')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>

                        <x-admin.form-group>
                            <label for="twitter_image" class="block text-sm font-medium text-gray-700">Twitter Image URL</label>
                            <x-admin.input 
                                type="url" 
                                id="twitter_image" 
                                name="twitter_image" 
                                placeholder="https://example.com/image.jpg"
                                value="{{ old('twitter_image', optional($page->metaInformation)->twitter_image) }}"
                            />
                            @error('twitter_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>
                    </div>

                    <x-admin.form-group>
                        <label for="twitter_title" class="block text-sm font-medium text-gray-700">Twitter Title</label>
                        <x-admin.input 
                            type="text" 
                            id="twitter_title" 
                            name="twitter_title" 
                            placeholder="Twitter sharing title"
                            value="{{ old('twitter_title', optional($page->metaInformation)->twitter_title) }}"
                        />
                        @error('twitter_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-admin.form-group>

                    <x-admin.form-group>
                        <label for="twitter_description" class="block text-sm font-medium text-gray-700">Twitter Description</label>
                        <textarea 
                            id="twitter_description" 
                            name="twitter_description" 
                            rows="3" 
                            placeholder="Twitter sharing description"
                            class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >{{ old('twitter_description', optional($page->metaInformation)->twitter_description) }}</textarea>
                        @error('twitter_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </x-admin.form-group>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-admin.form-group>
                            <label for="twitter_site" class="block text-sm font-medium text-gray-700">Twitter Site</label>
                            <x-admin.input 
                                type="text" 
                                id="twitter_site" 
                                name="twitter_site" 
                                placeholder="@yoursite"
                                value="{{ old('twitter_site', optional($page->metaInformation)->twitter_site) }}"
                            />
                            @error('twitter_site')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>

                        <x-admin.form-group>
                            <label for="twitter_creator" class="block text-sm font-medium text-gray-700">Twitter Creator</label>
                            <x-admin.input 
                                type="text" 
                                id="twitter_creator" 
                                name="twitter_creator" 
                                placeholder="@creator"
                                value="{{ old('twitter_creator', optional($page->metaInformation)->twitter_creator) }}"
                            />
                            @error('twitter_creator')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>
                    </div>
                </div>

                <!-- Advanced Tab -->
                <div id="content-advanced" class="tab-content space-y-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-admin.form-group>
                            <label for="canonical_url" class="block text-sm font-medium text-gray-700">Canonical URL</label>
                            <x-admin.input 
                                type="url" 
                                id="canonical_url" 
                                name="canonical_url" 
                                placeholder="https://example.com/canonical-page"
                                value="{{ old('canonical_url', optional($page->metaInformation)->canonical_url) }}"
                            />
                            @error('canonical_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Specify the canonical URL to avoid duplicate content issues.</p>
                        </x-admin.form-group>

                        <x-admin.form-group>
                            <label for="robots" class="block text-sm font-medium text-gray-700">Robots Meta</label>
                            <x-admin.select id="robots" name="robots">
                                <option value="index,follow" {{ old('robots', optional($page->metaInformation)->robots) == 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                                <option value="noindex,follow" {{ old('robots', optional($page->metaInformation)->robots) == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                                <option value="index,nofollow" {{ old('robots', optional($page->metaInformation)->robots) == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                                <option value="noindex,nofollow" {{ old('robots', optional($page->metaInformation)->robots) == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                            </x-admin.select>
                            @error('robots')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </x-admin.form-group>
                    </div>
                </div>
            </div>
        </x-admin.card>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <x-admin.button variant="secondary" type="button" onclick="window.history.back()">
                Cancel
            </x-admin.button>
            <x-admin.button type="submit">
                Update Page
            </x-admin.button>
        </div>
    </form>
</div>

<script>
// Tab functionality
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.add('hidden');
    });
    
    // Reset all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Activate selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-blue-500', 'text-blue-600');
}

// Toggle slug field edit mode
function toggleSlugEdit() {
    const slugInput = document.getElementById('slug');
    const editBtn = document.getElementById('edit-slug-btn');
    
    if (slugInput.readOnly) {
        slugInput.readOnly = false;
        slugInput.focus();
        editBtn.innerHTML = `
            <svg class="h-4 w-4 text-green-500 hover:text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        `;
        slugInput.classList.remove('bg-gray-50');
        slugInput.classList.add('bg-white');
    } else {
        slugInput.readOnly = true;
        editBtn.innerHTML = `
            <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        `;
        slugInput.classList.remove('bg-white');
        slugInput.classList.add('bg-gray-50');
    }
}

// Initialize with first tab active
document.addEventListener('DOMContentLoaded', function() {
    showTab('basic-seo');
});
</script>
@endsection