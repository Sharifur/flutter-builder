<?php

use App\Models\Admin;
use App\Models\Page;
use App\Models\MetaInformation;

beforeEach(function () {
    $this->admin = Admin::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'is_active' => true
    ]);
    
    $this->actingAs($this->admin, 'admin');
});

describe('Page Management', function () {
    
    test('admin can view pages index', function () {
        $pages = Page::factory()->count(3)->create(['created_by' => $this->admin->id]);
        
        $response = $this->get(route('admin.pages.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.index');
        $response->assertSee($pages[0]->title);
    });

    test('admin can create a new page', function () {
        $response = $this->get(route('admin.pages.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.create');
        $response->assertSee('Create New Page');
    });

    test('admin can store a new page with basic information', function () {
        $pageData = [
            'title' => 'Test Page Title',
            'slug' => 'test-page-title',
            'content' => 'This is test page content with sufficient length for testing purposes.',
            'status' => 'published',
            'show_breadcrumb' => true
        ];

        $response = $this->post(route('admin.pages.store'), $pageData);

        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('success', 'Page created successfully.');

        $this->assertDatabaseHas('pages', [
            'title' => 'Test Page Title',
            'slug' => 'test-page-title',
            'content' => 'This is test page content with sufficient length for testing purposes.',
            'status' => 'published',
            'show_breadcrumb' => true,
            'created_by' => $this->admin->id,
            'updated_by' => $this->admin->id
        ]);
    });

    test('admin can store a page with complete meta information', function () {
        $pageData = [
            'title' => 'SEO Optimized Page',
            'slug' => 'seo-optimized-page',
            'content' => 'This is comprehensive content for SEO testing with sufficient word count to meet the minimum requirements for good SEO scoring.',
            'status' => 'published',
            'show_breadcrumb' => true,
            // Basic SEO
            'meta_title' => 'SEO Optimized Page - Perfect Title Length for Search',
            'meta_description' => 'This is a perfectly optimized meta description that falls within the recommended character limit for search engines and provides excellent value.',
            'meta_keywords' => 'seo, optimization, testing, meta, keywords',
            // Open Graph
            'og_title' => 'SEO Optimized Page for Social Sharing',
            'og_description' => 'Social media optimized description for Facebook sharing.',
            'og_image' => 'https://example.com/og-image.jpg',
            'og_type' => 'article',
            'og_url' => 'https://example.com/seo-optimized-page',
            // Twitter Cards
            'twitter_card' => 'summary_large_image',
            'twitter_title' => 'SEO Page Twitter Title',
            'twitter_description' => 'Twitter optimized description for sharing.',
            'twitter_image' => 'https://example.com/twitter-image.jpg',
            'twitter_site' => '@testsite',
            'twitter_creator' => '@testcreator',
            // Advanced
            'canonical_url' => 'https://example.com/canonical-seo-page',
            'robots' => 'index,follow'
        ];

        $response = $this->post(route('admin.pages.store'), $pageData);

        $response->assertRedirect(route('admin.pages.index'));

        $page = Page::where('slug', 'seo-optimized-page')->first();
        $this->assertNotNull($page);

        $this->assertDatabaseHas('meta_information', [
            'metable_type' => Page::class,
            'metable_id' => $page->id,
            'meta_title' => 'SEO Optimized Page - Perfect Title Length for Search',
            'meta_description' => 'This is a perfectly optimized meta description that falls within the recommended character limit for search engines and provides excellent value.',
            'og_title' => 'SEO Optimized Page for Social Sharing',
            'twitter_card' => 'summary_large_image',
            'canonical_url' => 'https://example.com/canonical-seo-page'
        ]);
    });

    test('page creation validates required fields', function () {
        $response = $this->post(route('admin.pages.store'), []);

        $response->assertSessionHasErrors(['title', 'content', 'status']);
    });

    test('page creation validates unique slug', function () {
        Page::factory()->create(['slug' => 'existing-slug']);

        $response = $this->post(route('admin.pages.store'), [
            'title' => 'New Page',
            'slug' => 'existing-slug',
            'content' => 'Content here',
            'status' => 'draft'
        ]);

        $response->assertSessionHasErrors(['slug']);
    });

    test('admin can view a specific page', function () {
        $page = Page::factory()->withMeta()->create(['created_by' => $this->admin->id]);

        $response = $this->get(route('admin.pages.show', $page));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.show');
        $response->assertSee($page->title);
    });

    test('admin can edit a page', function () {
        $page = Page::factory()->withMeta()->create(['created_by' => $this->admin->id]);

        $response = $this->get(route('admin.pages.edit', $page));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.edit');
        $response->assertSee($page->title);
        $response->assertSee('Edit Page');
    });

    test('admin can update a page', function () {
        $page = Page::factory()->create(['created_by' => $this->admin->id]);

        $updateData = [
            'title' => 'Updated Page Title',
            'slug' => 'updated-page-title',
            'content' => 'Updated content with sufficient length for testing.',
            'status' => 'published',
            'show_breadcrumb' => false
        ];

        $response = $this->put(route('admin.pages.update', $page), $updateData);

        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('success', 'Page updated successfully.');

        $page->refresh();
        $this->assertEquals('Updated Page Title', $page->title);
        $this->assertEquals('updated-page-title', $page->slug);
        $this->assertEquals($this->admin->id, $page->updated_by);
    });

    test('admin can update page meta information', function () {
        $page = Page::factory()->withMeta()->create(['created_by' => $this->admin->id]);
        $originalMeta = $page->metaInformation;

        $updateData = [
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
            'status' => $page->status,
            'meta_title' => 'Updated Meta Title for SEO Testing Purpose',
            'meta_description' => 'Updated meta description that provides better information about the page content and improves search engine optimization.',
            'og_title' => 'Updated OG Title',
            'twitter_title' => 'Updated Twitter Title'
        ];

        $response = $this->put(route('admin.pages.update', $page), $updateData);

        $response->assertRedirect(route('admin.pages.index'));

        $page->refresh();
        $this->assertEquals('Updated Meta Title for SEO Testing Purpose', $page->metaInformation->meta_title);
        $this->assertEquals('Updated OG Title', $page->metaInformation->og_title);
    });

    test('admin can delete a page', function () {
        $page = Page::factory()->withMeta()->create(['created_by' => $this->admin->id]);
        $metaId = $page->metaInformation->id;

        $response = $this->delete(route('admin.pages.destroy', $page));

        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('success', 'Page deleted successfully.');

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
        $this->assertDatabaseMissing('meta_information', ['id' => $metaId]);
    });

    test('page index supports search functionality', function () {
        $page1 = Page::factory()->create([
            'title' => 'Laravel Tutorial',
            'created_by' => $this->admin->id
        ]);
        $page2 = Page::factory()->create([
            'title' => 'PHP Basics',
            'created_by' => $this->admin->id
        ]);

        $response = $this->get(route('admin.pages.index', ['search' => 'Laravel']));

        $response->assertStatus(200);
        $response->assertSee('Laravel Tutorial');
        $response->assertDontSee('PHP Basics');
    });

    test('page index supports status filtering', function () {
        $publishedPage = Page::factory()->create([
            'status' => 'published',
            'created_by' => $this->admin->id
        ]);
        $draftPage = Page::factory()->create([
            'status' => 'draft',
            'created_by' => $this->admin->id
        ]);

        $response = $this->get(route('admin.pages.index', ['status' => 'published']));

        $response->assertStatus(200);
        $response->assertSee($publishedPage->title);
        $response->assertDontSee($draftPage->title);
    });

    test('slug is auto-generated from title if not provided', function () {
        $pageData = [
            'title' => 'Auto Generated Slug Test!',
            'content' => 'Content for slug generation test.',
            'status' => 'draft'
        ];

        $response = $this->post(route('admin.pages.store'), $pageData);

        $response->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', [
            'title' => 'Auto Generated Slug Test!',
            'slug' => 'auto-generated-slug-test'
        ]);
    });

    test('page update validates unique slug excluding current page', function () {
        $page1 = Page::factory()->create(['slug' => 'existing-slug']);
        $page2 = Page::factory()->create(['slug' => 'another-slug']);

        $updateData = [
            'title' => 'Updated Title',
            'slug' => 'existing-slug',
            'content' => 'Updated content',
            'status' => 'published'
        ];

        $response = $this->put(route('admin.pages.update', $page2), $updateData);

        $response->assertSessionHasErrors(['slug']);
    });
});

describe('SEO Analysis API', function () {
    
    test('admin can analyze page SEO', function () {
        $seoData = [
            'title' => 'Perfect SEO Title Length for Search Engine Optimization',
            'description' => 'This is an optimal meta description that falls within the recommended character limit for search engines and provides excellent user experience.',
            'content' => str_repeat('This is quality content that provides value to users and meets SEO requirements. ', 20),
            'keywords' => 'seo, optimization, testing'
        ];

        $response = $this->post(route('admin.pages.analyze-seo'), $seoData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'score',
            'grade',
            'checks' => [
                'title_length',
                'description_length',
                'content_length',
                'keywords',
                'readability'
            ],
            'suggestions'
        ]);

        $data = $response->json();
        $this->assertGreaterThan(70, $data['score']);
        $this->assertContains($data['grade'], ['excellent', 'good', 'average']);
    });

    test('SEO analysis handles poor content', function () {
        $seoData = [
            'title' => 'Bad',
            'description' => 'Too short',
            'content' => 'Very short content.',
            'keywords' => ''
        ];

        $response = $this->post(route('admin.pages.analyze-seo'), $seoData);

        $response->assertStatus(200);
        $data = $response->json();
        
        $this->assertLessThan(50, $data['score']);
        $this->assertContains($data['grade'], ['poor', 'critical']);
        $this->assertNotEmpty($data['suggestions']);
    });
});

describe('Page Access Control', function () {
    
    test('unauthenticated users cannot access admin page routes', function () {
        auth('admin')->logout();
        
        $response = $this->get(route('admin.pages.index'));
        $response->assertRedirect(route('admin.login'));
    });

    test('inactive admin cannot access page routes', function () {
        $this->admin->update(['is_active' => false]);
        
        $response = $this->get(route('admin.pages.index'));
        $response->assertRedirect(route('admin.login'));
    });
});

describe('Page Relationships', function () {
    
    test('page belongs to creator admin', function () {
        $page = Page::factory()->create(['created_by' => $this->admin->id]);
        
        $this->assertInstanceOf(Admin::class, $page->creator);
        $this->assertEquals($this->admin->id, $page->creator->id);
    });

    test('page belongs to updater admin', function () {
        $updater = Admin::factory()->create();
        $page = Page::factory()->create([
            'created_by' => $this->admin->id,
            'updated_by' => $updater->id
        ]);
        
        $this->assertInstanceOf(Admin::class, $page->updater);
        $this->assertEquals($updater->id, $page->updater->id);
    });

    test('page has meta information relationship', function () {
        $page = Page::factory()->withMeta()->create(['created_by' => $this->admin->id]);
        
        $this->assertInstanceOf(MetaInformation::class, $page->metaInformation);
        $this->assertEquals($page->id, $page->metaInformation->metable_id);
        $this->assertEquals(Page::class, $page->metaInformation->metable_type);
    });

    test('admin has created pages relationship', function () {
        $pages = Page::factory()->count(3)->create(['created_by' => $this->admin->id]);
        
        $this->assertEquals(3, $this->admin->createdPages()->count());
        $this->assertTrue($this->admin->createdPages->contains($pages[0]));
    });
});