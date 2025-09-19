<?php

use App\Models\Admin;
use App\Models\Page;
use App\Models\MetaInformation;

beforeEach(function () {
    $this->admin = Admin::factory()->create();
    $this->actingAs($this->admin, 'admin');
});

describe('Page Management', function () {
    test('admin can view pages index', function () {
        $response = $this->get(route('admin.pages.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.index');
        $response->assertSee('Pages Management');
    });

    test('admin can create a new page', function () {
        $pageData = [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'This is test content for the page.',
            'status' => 'published',
            'show_breadcrumb' => true,
        ];

        $response = $this->post(route('admin.pages.store'), $pageData);

        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('success', 'Page created successfully.');

        $this->assertDatabaseHas('pages', [
            'title' => 'Test Page',
            'slug' => 'test-page',
            'status' => 'published',
            'created_by' => $this->admin->id,
        ]);
    });

    test('admin can create page with meta information', function () {
        $pageData = [
            'title' => 'SEO Optimized Page',
            'slug' => 'seo-page',
            'content' => 'Content with SEO optimization.',
            'status' => 'published',
            'show_breadcrumb' => true,
            'meta_title' => 'Custom Meta Title',
            'meta_description' => 'Custom meta description for SEO',
            'meta_keywords' => 'seo, test, page',
            'og_title' => 'Open Graph Title',
            'og_description' => 'Open Graph Description',
        ];

        $response = $this->post(route('admin.pages.store'), $pageData);

        $response->assertRedirect(route('admin.pages.index'));

        $page = Page::where('slug', 'seo-page')->first();
        expect($page)->not->toBeNull();
        expect($page->metaInformation)->not->toBeNull();
        expect($page->metaInformation->meta_title)->toBe('Custom Meta Title');
        expect($page->metaInformation->og_title)->toBe('Open Graph Title');
    });

    test('admin can view a page', function () {
        $page = Page::factory()->create(['created_by' => $this->admin->id]);

        $response = $this->get(route('admin.pages.show', $page));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.show');
        $response->assertSee($page->title);
    });

    test('admin can edit a page', function () {
        $page = Page::factory()->create(['created_by' => $this->admin->id]);

        $response = $this->get(route('admin.pages.edit', $page));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.edit');
        $response->assertSee($page->title);
    });

    test('admin can update a page', function () {
        $page = Page::factory()->create(['created_by' => $this->admin->id]);

        $updateData = [
            'title' => 'Updated Page Title',
            'slug' => 'updated-page-slug',
            'content' => 'Updated content',
            'status' => 'draft',
            'show_breadcrumb' => false,
        ];

        $response = $this->put(route('admin.pages.update', $page), $updateData);

        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('success', 'Page updated successfully.');

        $page->refresh();
        expect($page->title)->toBe('Updated Page Title');
        expect($page->slug)->toBe('updated-page-slug');
        expect($page->status)->toBe('draft');
        expect($page->updated_by)->toBe($this->admin->id);
    });

    test('admin can delete a page', function () {
        $page = Page::factory()->create(['created_by' => $this->admin->id]);

        $response = $this->delete(route('admin.pages.destroy', $page));

        $response->assertRedirect(route('admin.pages.index'));
        $response->assertSessionHas('success', 'Page deleted successfully.');

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    });

    test('admin can search pages', function () {
        Page::factory()->create([
            'title' => 'Searchable Page',
            'created_by' => $this->admin->id,
        ]);
        
        Page::factory()->create([
            'title' => 'Other Page',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get(route('admin.pages.index', ['search' => 'Searchable']));

        $response->assertStatus(200);
        $response->assertSee('Searchable Page');
        $response->assertDontSee('Other Page');
    });

    test('admin can filter pages by status', function () {
        Page::factory()->create([
            'title' => 'Published Page',
            'status' => 'published',
            'created_by' => $this->admin->id,
        ]);
        
        Page::factory()->create([
            'title' => 'Draft Page',
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->get(route('admin.pages.index', ['status' => 'published']));

        $response->assertStatus(200);
        $response->assertSee('Published Page');
        $response->assertDontSee('Draft Page');
    });

    test('slug is auto-generated from title when not provided', function () {
        $pageData = [
            'title' => 'Auto Generated Slug Page',
            'content' => 'Test content',
            'status' => 'draft',
        ];

        $this->post(route('admin.pages.store'), $pageData);

        $this->assertDatabaseHas('pages', [
            'title' => 'Auto Generated Slug Page',
            'slug' => 'auto-generated-slug-page',
        ]);
    });

    test('page requires title and content', function () {
        $response = $this->post(route('admin.pages.store'), [
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors(['title', 'content']);
    });

    test('slug must be unique', function () {
        Page::factory()->create([
            'slug' => 'unique-slug',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->post(route('admin.pages.store'), [
            'title' => 'Another Page',
            'slug' => 'unique-slug',
            'content' => 'Test content',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors(['slug']);
    });
});