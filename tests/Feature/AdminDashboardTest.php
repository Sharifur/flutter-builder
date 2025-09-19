<?php

use App\Models\Admin;
use App\Models\Page;

beforeEach(function () {
    $this->admin = Admin::factory()->create();
    $this->actingAs($this->admin, 'admin');
});

describe('Admin Dashboard', function () {
    test('admin can view dashboard', function () {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertSee('Dashboard');
    });

    test('dashboard shows correct statistics', function () {
        // Create test data
        Page::factory()->count(5)->create(['status' => 'published', 'created_by' => $this->admin->id]);
        Page::factory()->count(3)->create(['status' => 'draft', 'created_by' => $this->admin->id]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('stats');
        
        $stats = $response->viewData('stats');
        expect($stats['total_pages'])->toBe(8);
        expect($stats['published_pages'])->toBe(5);
        expect($stats['draft_pages'])->toBe(3);
    });

    test('dashboard shows recent pages', function () {
        $recentPage = Page::factory()->create([
            'title' => 'Recent Test Page',
            'created_by' => $this->admin->id,
            'created_at' => now(),
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Recent Test Page');
        $response->assertViewHas('recent_pages');
    });

    test('dashboard shows empty state when no pages exist', function () {
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('No pages yet');
        $response->assertSee('Create Page');
    });
});