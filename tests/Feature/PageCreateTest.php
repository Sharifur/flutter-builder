<?php

use App\Models\Admin;
use App\Models\Page;
use App\Models\MetaInformation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Admin::factory()->create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'is_active' => true,
        'role' => 'admin'
    ]);
    
    $this->actingAs($this->admin, 'admin');
});

describe('Page Creation Tests', function () {
    
    test('admin can access page creation form', function () {
        $response = $this->get(route('admin.pages.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.create');
        $response->assertSee('Create New Page');
        $response->assertSee('Page Information');
        $response->assertSee('Meta Information');
        $response->assertSee('title');
        $response->assertSee('content');
        $response->assertSee('status');
    });
    
    test('page creation form contains all required fields', function () {
        $response = $this->get(route('admin.pages.create'));
        
        $response->assertSee('name="title"', false);
        $response->assertSee('name="slug"', false);
        $response->assertSee('name="content"', false);
        $response->assertSee('name="status"', false);
        $response->assertSee('name="show_breadcrumb"', false);
        
        // Meta fields
        $response->assertSee('name="meta_title"', false);
        $response->assertSee('name="meta_description"', false);
        $response->assertSee('name="meta_keywords"', false);
        $response->assertSee('name="focus_keyword"', false);
        
        // Open Graph fields
        $response->assertSee('name="og_title"', false);
        $response->assertSee('name="og_description"', false);
        $response->assertSee('name="og_image"', false);
        $response->assertSee('name="og_type"', false);
        
        // Twitter Card fields
        $response->assertSee('name="twitter_card"', false);
        $response->assertSee('name="twitter_title"', false);
        $response->assertSee('name="twitter_description"', false);
        $response->assertSee('name="twitter_image"', false);
        
        // Advanced fields
        $response->assertSee('name="canonical_url"', false);
        $response->assertSee('name="robots"', false);
    });
    
    test('admin can create page with minimal required fields', function () {
        $pageData = [
            'title' => 'Simple Test Page',
            'content' => 'This is simple test content for our page.',
            'status' => 'draft'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('success', 'Page created successfully.');
        
        $this->assertDatabaseHas('pages', [
            'title' => 'Simple Test Page',
            'slug' => 'simple-test-page', // Should be auto-generated
            'content' => 'This is simple test content for our page.',
            'status' => 'draft',
            'show_breadcrumb' => false,
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id
        ]);
    });
    
    test('admin can create page with custom slug', function () {
        $pageData = [
            'title' => 'Custom Slug Page',
            'slug' => 'my-custom-page-url',
            'content' => 'Content for custom slug page.',
            'status' => 'published'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $response->assertRedirect(route('admin.pages.index'));
        
        $this->assertDatabaseHas('pages', [
            'title' => 'Custom Slug Page',
            'slug' => 'my-custom-page-url',
            'status' => 'published'
        ]);
    });
    
    test('admin can create published page', function () {
        $pageData = [
            'title' => 'Published Page',
            'content' => 'This page should be published immediately.',
            'status' => 'published',
            'show_breadcrumb' => true
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $response->assertRedirect(route('admin.pages.index'));
        
        $page = Page::where('title', 'Published Page')->first();
        expect($page->status)->toBe('published');
        expect($page->show_breadcrumb)->toBeTrue();
    });
    
    test('admin can create page with basic SEO meta information', function () {
        $pageData = [
            'title' => 'SEO Optimized Page',
            'slug' => 'seo-optimized-page',
            'content' => 'This is comprehensive content that provides valuable information to users and search engines alike.',
            'status' => 'published',
            'meta_title' => 'SEO Optimized Page - Best Practices',
            'meta_description' => 'Learn about SEO optimization with this comprehensive guide that covers all the essential elements.',
            'meta_keywords' => 'seo, optimization, meta, search engines',
            'focus_keyword' => 'seo optimization'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $response->assertRedirect(route('admin.pages.index'));
        
        $page = Page::where('slug', 'seo-optimized-page')->first();
        $this->assertNotNull($page);
        
        $this->assertDatabaseHas('meta_information', [
            'metable_type' => Page::class,
            'metable_id' => $page->id,
            'meta_title' => 'SEO Optimized Page - Best Practices',
            'meta_description' => 'Learn about SEO optimization with this comprehensive guide that covers all the essential elements.',
            'meta_keywords' => 'seo, optimization, meta, search engines',
            'focus_keyword' => 'seo optimization'
        ]);
    });
    
    test('admin can create page with Open Graph meta information', function () {
        $pageData = [
            'title' => 'Social Media Page',
            'content' => 'Content optimized for social media sharing.',
            'status' => 'published',
            'og_title' => 'Amazing Social Media Page',
            'og_description' => 'This page is perfectly optimized for sharing on Facebook and other social platforms.',
            'og_image' => 'https://example.com/social-image.jpg',
            'og_type' => 'article',
            'og_url' => 'https://example.com/social-media-page',
            'og_site_name' => 'Test Site'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $response->assertRedirect(route('admin.pages.index'));
        
        $page = Page::where('title', 'Social Media Page')->first();
        
        $this->assertDatabaseHas('meta_information', [
            'metable_type' => Page::class,
            'metable_id' => $page->id,
            'og_title' => 'Amazing Social Media Page',
            'og_description' => 'This page is perfectly optimized for sharing on Facebook and other social platforms.',
            'og_image' => 'https://example.com/social-image.jpg',
            'og_type' => 'article',
            'og_url' => 'https://example.com/social-media-page',
            'og_site_name' => 'Test Site'
        ]);
    });
    
    test('admin can create page with Twitter Card meta information', function () {
        $pageData = [
            'title' => 'Twitter Optimized Page',
            'content' => 'Content optimized for Twitter sharing.',
            'status' => 'published',
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'Twitter Optimized Page Title',
            'twitter_description' => 'Perfect description for Twitter cards.',
            'twitter_image' => 'https://example.com/twitter-card.jpg',
            'twitter_site' => '@testsite',
            'twitter_creator' => '@testcreator'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $page = Page::where('title', 'Twitter Optimized Page')->first();
        
        $this->assertDatabaseHas('meta_information', [
            'metable_type' => Page::class,
            'metable_id' => $page->id,
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'Twitter Optimized Page Title',
            'twitter_description' => 'Perfect description for Twitter cards.',
            'twitter_image' => 'https://example.com/twitter-card.jpg',
            'twitter_site' => '@testsite',
            'twitter_creator' => '@testcreator'
        ]);
    });
    
    test('admin can create page with advanced meta settings', function () {
        $pageData = [
            'title' => 'Advanced Meta Page',
            'content' => 'Page with advanced meta configurations.',
            'status' => 'published',
            'canonical_url' => 'https://example.com/canonical-page',
            'robots' => 'index,follow',
            'schema_markup' => '{"@context":"https://schema.org","@type":"Article","headline":"Advanced Meta Page"}'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $response->assertRedirect(route('admin.pages.index'));
        
        $page = Page::where('title', 'Advanced Meta Page')->first();
        
        $this->assertDatabaseHas('meta_information', [
            'metable_type' => Page::class,
            'metable_id' => $page->id,
            'canonical_url' => 'https://example.com/canonical-page',
            'robots' => 'index,follow',
            'schema_markup' => '{"@context":"https://schema.org","@type":"Article","headline":"Advanced Meta Page"}'
        ]);
    });
    
    test('admin can create page with complete meta information', function () {
        $pageData = [
            'title' => 'Complete Meta Page',
            'slug' => 'complete-meta-page',
            'content' => 'This page has comprehensive meta information configured for optimal SEO and social media performance.',
            'status' => 'published',
            'show_breadcrumb' => true,
            
            // Basic SEO
            'meta_title' => 'Complete Meta Page - Full SEO Configuration',
            'meta_description' => 'This page demonstrates complete meta information setup with all fields properly configured for maximum SEO impact.',
            'meta_keywords' => 'complete, meta, seo, optimization, social media',
            'focus_keyword' => 'complete meta optimization',
            
            // Open Graph
            'og_title' => 'Complete Meta Page for Social Sharing',
            'og_description' => 'Social media optimized version of our complete meta page.',
            'og_image' => 'https://example.com/complete-og-image.jpg',
            'og_type' => 'website',
            'og_url' => 'https://example.com/complete-meta-page',
            'og_site_name' => 'Complete Test Site',
            
            // Twitter Cards
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'Complete Meta Page Twitter',
            'twitter_description' => 'Twitter optimized description for complete meta page.',
            'twitter_image' => 'https://example.com/complete-twitter.jpg',
            'twitter_site' => '@completesite',
            'twitter_creator' => '@completecreator',
            
            // Advanced
            'canonical_url' => 'https://example.com/complete-canonical',
            'robots' => 'index,follow',
            'schema_markup' => '{"@context":"https://schema.org","@type":"WebPage","name":"Complete Meta Page"}'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('success', 'Page created successfully.');
        
        $page = Page::where('slug', 'complete-meta-page')->first();
        $this->assertNotNull($page);
        
        // Verify page data
        expect($page->title)->toBe('Complete Meta Page');
        expect($page->slug)->toBe('complete-meta-page');
        expect($page->status)->toBe('published');
        expect($page->show_breadcrumb)->toBeTrue();
        expect($page->created_by)->toBe($this->admin->id);
        
        // Verify meta information
        $meta = $page->metaInformation;
        $this->assertNotNull($meta);
        
        expect($meta->meta_title)->toBe('Complete Meta Page - Full SEO Configuration');
        expect($meta->meta_description)->toBe('This page demonstrates complete meta information setup with all fields properly configured for maximum SEO impact.');
        expect($meta->meta_keywords)->toBe('complete, meta, seo, optimization, social media');
        expect($meta->focus_keyword)->toBe('complete meta optimization');
        
        expect($meta->og_title)->toBe('Complete Meta Page for Social Sharing');
        expect($meta->og_description)->toBe('Social media optimized version of our complete meta page.');
        expect($meta->og_image)->toBe('https://example.com/complete-og-image.jpg');
        expect($meta->og_type)->toBe('website');
        
        expect($meta->twitter_card)->toBe('summary_large_image');
        expect($meta->twitter_title)->toBe('Complete Meta Page Twitter');
        expect($meta->twitter_site)->toBe('@completesite');
        
        expect($meta->canonical_url)->toBe('https://example.com/complete-canonical');
        expect($meta->robots)->toBe('index,follow');
    });
});

describe('Page Creation Validation Tests', function () {
    
    test('page creation requires title field', function () {
        $response = $this->post(route('admin.pages.store'), [
            'content' => 'Content without title',
            'status' => 'draft'
        ]);
        
        $response->assertSessionHasErrors(['title']);
    });
    
    test('page creation requires content field', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Title without content',
            'status' => 'draft'
        ]);
        
        $response->assertSessionHasErrors(['content']);
    });
    
    test('page creation requires status field', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Title without status',
            'content' => 'Content without status'
        ]);
        
        $response->assertSessionHasErrors(['status']);
    });
    
    test('page creation validates title length', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => str_repeat('a', 256), // Exceeds max length
            'content' => 'Valid content',
            'status' => 'draft'
        ]);
        
        $response->assertSessionHasErrors(['title']);
    });
    
    test('page creation validates unique slug', function () {
        // Create existing page
        Page::factory()->create(['slug' => 'existing-page']);
        
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'New Page',
            'slug' => 'existing-page',
            'content' => 'New content',
            'status' => 'draft'
        ]);
        
        $response->assertSessionHasErrors(['slug']);
    });
    
    test('page creation validates status enum', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Valid Title',
            'content' => 'Valid content',
            'status' => 'invalid_status'
        ]);
        
        $response->assertSessionHasErrors(['status']);
    });
    
    test('page creation validates meta title length', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Valid Title',
            'content' => 'Valid content',
            'status' => 'draft',
            'meta_title' => str_repeat('a', 61) // Exceeds recommended length
        ]);
        
        $response->assertSessionHasErrors(['meta_title']);
    });
    
    test('page creation validates meta description length', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Valid Title',
            'content' => 'Valid content',
            'status' => 'draft',
            'meta_description' => str_repeat('a', 321) // Exceeds max length
        ]);
        
        $response->assertSessionHasErrors(['meta_description']);
    });
    
    test('page creation validates URL fields format', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Valid Title',
            'content' => 'Valid content',
            'status' => 'draft',
            'canonical_url' => 'invalid-url',
            'og_url' => 'also-invalid',
            'og_image' => 'not-a-url',
            'twitter_image' => 'invalid-image-url'
        ]);
        
        $response->assertSessionHasErrors(['canonical_url', 'og_url', 'og_image', 'twitter_image']);
    });
    
    test('page creation validates robots meta format', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Valid Title',
            'content' => 'Valid content',
            'status' => 'draft',
            'robots' => str_repeat('a', 60) // Exceeds max length
        ]);
        
        $response->assertSessionHasErrors(['robots']);
    });
});

describe('Page Creation Security Tests', function () {
    
    test('guest cannot access page creation form', function () {
        auth('admin')->logout();
        
        $response = $this->get(route('admin.pages.create'));
        
        $response->assertRedirect(route('admin.login'));
    });
    
    test('guest cannot create pages', function () {
        auth('admin')->logout();
        
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Unauthorized Page',
            'content' => 'This should not be created',
            'status' => 'published'
        ]);
        
        $response->assertRedirect(route('admin.login'));
        
        $this->assertDatabaseMissing('pages', [
            'title' => 'Unauthorized Page'
        ]);
    });
    
    test('inactive admin cannot create pages', function () {
        $this->admin->update(['is_active' => false]);
        
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Inactive Admin Page',
            'content' => 'Should not be created',
            'status' => 'published'
        ]);
        
        $response->assertRedirect(route('admin.login'));
        
        $this->assertDatabaseMissing('pages', [
            'title' => 'Inactive Admin Page'
        ]);
    });
    
    test('page creation sanitizes input content', function () {
        $pageData = [
            'title' => 'Test <script>alert("xss")</script> Page',
            'content' => 'Content with <script>malicious();</script> code',
            'status' => 'draft'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $response->assertRedirect(route('admin.pages.index'));
        
        $page = Page::where('title', 'LIKE', '%Test%Page%')->first();
        
        // Verify that scripts are handled appropriately
        // Note: The exact sanitization depends on your implementation
        expect($page->title)->not->toContain('<script>');
        expect($page->content)->not->toContain('<script>malicious();</script>');
    });
});

describe('Page Creation Business Logic Tests', function () {
    
    test('slug is auto-generated when not provided', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Auto Generated Slug Page!',
            'content' => 'Content for auto slug',
            'status' => 'draft'
        ]);
        
        $page = Page::where('title', 'Auto Generated Slug Page!')->first();
        
        expect($page->slug)->toBe('auto-generated-slug-page');
    });
    
    test('created_by and updated_by are set correctly', function () {
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Attribution Test Page',
            'content' => 'Testing creator attribution',
            'status' => 'draft'
        ]);
        
        $page = Page::where('title', 'Attribution Test Page')->first();
        
        expect($page->created_by)->toBe($this->admin->id);
        expect($page->updated_by)->toBe($this->admin->id);
    });
    
    test('page creation triggers SEO analysis when meta data provided', function () {
        $pageData = [
            'title' => 'SEO Analysis Test Page',
            'content' => 'This is comprehensive content that provides valuable information to users. It contains more than 300 words which is considered good for SEO. The content is well-structured and informative, covering all the important aspects of the topic in detail. This ensures that search engines can properly understand and index the content.',
            'status' => 'published',
            'meta_title' => 'SEO Analysis Test Page - Perfect Length',
            'meta_description' => 'This meta description is optimized for search engines and provides a clear summary of what users can expect.',
            'focus_keyword' => 'seo analysis'
        ];
        
        $response = $this->post(route('admin.pages.store'), $pageData);
        
        $page = Page::where('title', 'SEO Analysis Test Page')->first();
        $meta = $page->metaInformation;
        
        // Verify SEO score is calculated
        expect($meta->seo_score)->toBeGreaterThan(0);
        expect($meta->seo_score)->toBeLessThanOrEqual(100);
    });
    
    test('different admin roles can create pages', function () {
        $manager = Admin::factory()->create([
            'role' => 'manager',
            'is_active' => true
        ]);
        
        $editor = Admin::factory()->create([
            'role' => 'editor',
            'is_active' => true
        ]);
        
        // Test manager can create pages
        $this->actingAs($manager, 'admin');
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Manager Created Page',
            'content' => 'Content by manager',
            'status' => 'draft'
        ]);
        $response->assertRedirect(route('admin.pages.index'));
        
        // Test editor can create pages
        $this->actingAs($editor, 'admin');
        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Editor Created Page',
            'content' => 'Content by editor',
            'status' => 'draft'
        ]);
        $response->assertRedirect(route('admin.pages.index'));
        
        $this->assertDatabaseHas('pages', ['title' => 'Manager Created Page']);
        $this->assertDatabaseHas('pages', ['title' => 'Editor Created Page']);
    });
});