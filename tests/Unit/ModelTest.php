<?php

use App\Models\Admin;
use App\Models\Page;
use App\Models\MetaInformation;
use App\Models\SiteSetting;

describe('Admin Model', function () {
    test('admin has correct fillable attributes', function () {
        $admin = new Admin();
        
        expect($admin->getFillable())->toEqual([
            'name',
            'email',
            'password',
            'is_active',
            'role',
        ]);
    });

    test('admin has correct hidden attributes', function () {
        $admin = new Admin();
        
        expect($admin->getHidden())->toEqual([
            'password',
            'remember_token',
        ]);
    });

    test('admin can have many created pages', function () {
        $admin = Admin::factory()->create();
        $page = Page::factory()->create(['created_by' => $admin->id]);

        expect($admin->createdPages)->toHaveCount(1);
        expect($admin->createdPages->first()->id)->toBe($page->id);
    });

    test('admin active scope works correctly', function () {
        Admin::factory()->create(['is_active' => true]);
        Admin::factory()->create(['is_active' => false]);

        $activeAdmins = Admin::active()->get();

        expect($activeAdmins)->toHaveCount(1);
        expect($activeAdmins->first()->is_active)->toBeTrue();
    });
});

describe('Page Model', function () {
    test('page has correct fillable attributes', function () {
        $page = new Page();
        
        expect($page->getFillable())->toEqual([
            'title',
            'slug', 
            'content',
            'show_breadcrumb',
            'status',
            'created_by',
            'updated_by'
        ]);
    });

    test('page belongs to creator admin', function () {
        $admin = Admin::factory()->create();
        $page = Page::factory()->create(['created_by' => $admin->id]);

        expect($page->creator->id)->toBe($admin->id);
    });

    test('page can have meta information', function () {
        $page = Page::factory()->create();
        $meta = MetaInformation::factory()->create([
            'metable_type' => Page::class,
            'metable_id' => $page->id,
        ]);

        expect($page->metaInformation->id)->toBe($meta->id);
    });

    test('page published scope works correctly', function () {
        Page::factory()->create(['status' => 'published']);
        Page::factory()->create(['status' => 'draft']);

        $publishedPages = Page::published()->get();

        expect($publishedPages)->toHaveCount(1);
        expect($publishedPages->first()->status)->toBe('published');
    });

    test('page draft scope works correctly', function () {
        Page::factory()->create(['status' => 'published']);
        Page::factory()->create(['status' => 'draft']);

        $draftPages = Page::draft()->get();

        expect($draftPages)->toHaveCount(1);
        expect($draftPages->first()->status)->toBe('draft');
    });

    test('page route key name is slug', function () {
        $page = new Page();
        
        expect($page->getRouteKeyName())->toBe('slug');
    });
});

describe('MetaInformation Model', function () {
    test('meta information has correct fillable attributes', function () {
        $meta = new MetaInformation();
        
        expect($meta->getFillable())->toContain('meta_title');
        expect($meta->getFillable())->toContain('meta_description');
        expect($meta->getFillable())->toContain('og_title');
        expect($meta->getFillable())->toContain('twitter_card');
    });

    test('meta information belongs to metable', function () {
        $admin = Admin::factory()->create();
        $page = Page::factory()->create(['created_by' => $admin->id]);
        $meta = MetaInformation::factory()->create([
            'metable_type' => Page::class,
            'metable_id' => $page->id,
        ]);

        expect($meta->metable->id)->toBe($page->id);
        expect($meta->metable)->toBeInstanceOf(Page::class);
    });

    test('effective meta title returns custom title when available', function () {
        $admin = Admin::factory()->create();
        $page = Page::factory()->create(['title' => 'Page Title', 'created_by' => $admin->id]);
        $meta = MetaInformation::factory()->create([
            'metable_type' => Page::class,
            'metable_id' => $page->id,
            'meta_title' => 'Custom Meta Title',
        ]);

        expect($meta->effective_meta_title)->toBe('Custom Meta Title');
    });

    test('effective meta title falls back to page title template', function () {
        $admin = Admin::factory()->create();
        $page = Page::factory()->create(['title' => 'Test Page', 'created_by' => $admin->id]);
        $meta = MetaInformation::factory()->create([
            'metable_type' => Page::class,
            'metable_id' => $page->id,
            'meta_title' => null,
        ]);

        expect($meta->effective_meta_title)->toContain('Test Page');
    });
});

describe('SiteSetting Model', function () {
    test('site setting has correct fillable attributes', function () {
        $setting = new SiteSetting();
        
        expect($setting->getFillable())->toEqual([
            'key',
            'value',
            'type',
            'group',
            'description'
        ]);
    });

    test('site setting can get and set values', function () {
        SiteSetting::set('test.key', 'test value');
        
        expect(SiteSetting::get('test.key'))->toBe('test value');
        expect(SiteSetting::get('nonexistent.key', 'default'))->toBe('default');
    });

    test('site setting handles boolean values correctly', function () {
        SiteSetting::set('test.boolean', true, 'boolean');
        
        $setting = SiteSetting::where('key', 'test.boolean')->first();
        expect($setting->value)->toBeTrue();
    });

    test('site setting handles json values correctly', function () {
        $testData = ['key' => 'value', 'nested' => ['data' => 'test']];
        SiteSetting::set('test.json', $testData, 'json');
        
        $setting = SiteSetting::where('key', 'test.json')->first();
        expect($setting->value)->toBe($testData);
    });

    test('site setting group scope works correctly', function () {
        SiteSetting::create(['key' => 'meta.title', 'value' => 'test', 'group' => 'meta', 'type' => 'text']);
        SiteSetting::create(['key' => 'seo.robots', 'value' => 'index', 'group' => 'seo', 'type' => 'text']);

        $metaSettings = SiteSetting::group('meta')->get();

        expect($metaSettings)->toHaveCount(1);
        expect($metaSettings->first()->key)->toBe('meta.title');
    });
});