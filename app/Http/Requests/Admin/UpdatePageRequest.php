<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check() && auth('admin')->user()->is_active;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic Page Fields
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($this->route('page'))
            ],
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'show_breadcrumb' => 'nullable|boolean',

            // Basic Meta Fields
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords' => 'nullable|string|max:500',

            // Open Graph Fields
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|url|max:500',
            'og_type' => 'nullable|in:website,article,product',
            'og_url' => 'nullable|url|max:500',

            // Twitter Card Fields
            'twitter_card' => 'nullable|in:summary,summary_large_image,app,player',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|url|max:500',
            'twitter_site' => 'nullable|string|max:100',
            'twitter_creator' => 'nullable|string|max:100',

            // Advanced Meta Fields
            'canonical_url' => 'nullable|url|max:500',
            'robots' => 'nullable|in:index,follow,noindex,follow,index,nofollow,noindex,nofollow',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The page title is required.',
            'content.required' => 'The page content is required.',
            'status.required' => 'Please select a page status.',
            'status.in' => 'The status must be either draft or published.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'meta_title.max' => 'Meta title should not exceed 60 characters for optimal SEO.',
            'meta_description.max' => 'Meta description should not exceed 320 characters.',
            'og_image.url' => 'Open Graph image must be a valid URL.',
            'twitter_image.url' => 'Twitter image must be a valid URL.',
            'canonical_url.url' => 'Canonical URL must be a valid URL.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // If slug is empty, generate it from title
            if (empty($this->slug) && !empty($this->title)) {
                $this->merge([
                    'slug' => \Illuminate\Support\Str::slug($this->title)
                ]);
            } elseif (!empty($this->slug)) {
                // Always ensure proper slug formatting
                $this->merge([
                    'slug' => \Illuminate\Support\Str::slug($this->slug)
                ]);
            }
        });
    }
}
