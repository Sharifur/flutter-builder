<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetaInformationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'metable_type' => Page::class,
            'metable_id' => Page::factory(),
            'meta_title' => fake()->sentence(6, false),
            'meta_description' => fake()->sentence(15),
            'meta_keywords' => implode(', ', fake()->words(5)),
            'og_title' => fake()->sentence(5, false),
            'og_description' => fake()->sentence(12),
            'og_image' => fake()->imageUrl(1200, 630),
            'og_type' => 'website',
            'og_url' => fake()->url(),
            'twitter_card' => 'summary_large_image',
            'twitter_title' => fake()->sentence(4, false),
            'twitter_description' => fake()->sentence(10),
            'twitter_image' => fake()->imageUrl(1200, 630),
            'twitter_site' => '@' . fake()->userName(),
            'twitter_creator' => '@' . fake()->userName(),
            'canonical_url' => fake()->url(),
            'robots' => fake()->randomElement(['index,follow', 'noindex,nofollow', 'index,nofollow']),
        ];
    }
}