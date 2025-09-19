<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4, false);
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(3, true),
            'show_breadcrumb' => fake()->boolean(80),
            'status' => fake()->randomElement(['draft', 'published']),
            'created_by' => Admin::factory(),
            'updated_by' => function (array $attributes) {
                return $attributes['created_by'];
            },
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function withMeta(): static
    {
        return $this->afterCreating(function ($page) {
            $page->metaInformation()->create([
                'meta_title' => fake()->sentence(6, false),
                'meta_description' => fake()->sentence(15),
                'meta_keywords' => implode(', ', fake()->words(5)),
                'og_title' => fake()->sentence(5, false),
                'og_description' => fake()->sentence(12),
                'og_image' => fake()->imageUrl(1200, 630),
                'twitter_title' => fake()->sentence(4, false),
                'twitter_description' => fake()->sentence(10),
            ]);
        });
    }
}