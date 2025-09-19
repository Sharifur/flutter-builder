<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Meta Defaults
            [
                'key' => 'meta.default_title_template',
                'value' => '{{page_title}} - {{site_name}}',
                'type' => 'text',
                'group' => 'meta',
                'description' => 'Default template for page titles. Use {{page_title}} and {{site_name}} placeholders.'
            ],
            [
                'key' => 'meta.default_description',
                'value' => 'Advanced Laravel Admin Panel with comprehensive meta information management system.',
                'type' => 'text',
                'group' => 'meta',
                'description' => 'Default meta description when pages don\'t have custom descriptions.'
            ],
            [
                'key' => 'meta.default_keywords',
                'value' => 'laravel, admin panel, meta information, seo, cms',
                'type' => 'text',
                'group' => 'meta',
                'description' => 'Default meta keywords for SEO.'
            ],
            
            // Open Graph Defaults
            [
                'key' => 'og.default_image',
                'value' => '/images/og-default.jpg',
                'type' => 'text',
                'group' => 'social',
                'description' => 'Default Open Graph image URL.'
            ],
            [
                'key' => 'og.site_name',
                'value' => config('app.name'),
                'type' => 'text',
                'group' => 'social',
                'description' => 'Site name for Open Graph meta tags.'
            ],
            
            // Twitter Defaults
            [
                'key' => 'twitter.site',
                'value' => '@yoursite',
                'type' => 'text',
                'group' => 'social',
                'description' => 'Default Twitter site username.'
            ],
            [
                'key' => 'twitter.creator',
                'value' => '@yourcreator',
                'type' => 'text',
                'group' => 'social',
                'description' => 'Default Twitter creator username.'
            ],
            [
                'key' => 'twitter.default_card',
                'value' => 'summary_large_image',
                'type' => 'text',
                'group' => 'social',
                'description' => 'Default Twitter card type.'
            ],
            
            // SEO Settings
            [
                'key' => 'seo.robots_default',
                'value' => 'index,follow',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Default robots meta tag value.'
            ],
            [
                'key' => 'seo.enable_breadcrumbs',
                'value' => true,
                'type' => 'boolean',
                'group' => 'seo',
                'description' => 'Enable breadcrumbs by default for new pages.'
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}