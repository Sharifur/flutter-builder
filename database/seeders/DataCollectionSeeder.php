<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataCollection;
use App\Models\CollectionField;

class DataCollectionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create the default Users collection
        $usersCollection = DataCollection::create([
            'name' => 'Users',
            'slug' => 'users',
            'description' => 'Default user collection for app authentication and user management',
            'icon' => 'users',
            'is_system' => true,
            'is_active' => true,
            'sort_order' => 1,
            'settings' => [
                'auth_enabled' => true,
                'registration_enabled' => true,
                'email_verification' => false,
                'auto_approve' => true,
            ],
            'permissions' => [
                'create' => ['*'],
                'read' => ['*'],
                'update' => ['owner', 'admin'],
                'delete' => ['admin'],
            ],
        ]);

        // Define fields for the Users collection
        $userFields = [
            [
                'name' => 'name',
                'label' => 'Full Name',
                'type' => 'text',
                'is_required' => true,
                'is_searchable' => true,
                'sort_order' => 1,
                'validation_rules' => ['min:2', 'max:100'],
            ],
            [
                'name' => 'email',
                'label' => 'Email Address',
                'type' => 'email',
                'is_required' => true,
                'is_unique' => true,
                'is_searchable' => true,
                'sort_order' => 2,
                'validation_rules' => ['max:255'],
            ],
            [
                'name' => 'password',
                'label' => 'Password',
                'type' => 'password',
                'is_required' => true,
                'sort_order' => 3,
                'validation_rules' => ['min:8'],
                'ui_settings' => [
                    'show_in_list' => false,
                    'show_in_detail' => false,
                ],
            ],
            [
                'name' => 'phone',
                'label' => 'Phone Number',
                'type' => 'text',
                'is_required' => false,
                'sort_order' => 4,
                'validation_rules' => ['regex:/^[\+]?[1-9][\d]{0,15}$/'],
            ],
            [
                'name' => 'avatar',
                'label' => 'Profile Picture',
                'type' => 'image',
                'is_required' => false,
                'sort_order' => 5,
                'field_options' => [
                    'allowed_types' => ['jpg', 'jpeg', 'png', 'gif'],
                    'max_size' => 2048,
                    'path' => 'avatars',
                ],
            ],
            [
                'name' => 'date_of_birth',
                'label' => 'Date of Birth',
                'type' => 'date',
                'is_required' => false,
                'sort_order' => 6,
            ],
            [
                'name' => 'gender',
                'label' => 'Gender',
                'type' => 'select',
                'is_required' => false,
                'sort_order' => 7,
                'field_options' => [
                    'options' => [
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                        'prefer_not_to_say' => 'Prefer not to say',
                    ],
                ],
            ],
            [
                'name' => 'bio',
                'label' => 'Biography',
                'type' => 'textarea',
                'is_required' => false,
                'sort_order' => 8,
                'validation_rules' => ['max:500'],
            ],
            [
                'name' => 'is_active',
                'label' => 'Active Status',
                'type' => 'boolean',
                'default_value' => '1',
                'is_required' => false,
                'sort_order' => 9,
            ],
            [
                'name' => 'timezone',
                'label' => 'Timezone',
                'type' => 'text',
                'default_value' => 'UTC',
                'is_required' => false,
                'sort_order' => 10,
            ],
            [
                'name' => 'language',
                'label' => 'Preferred Language',
                'type' => 'select',
                'default_value' => 'en',
                'is_required' => false,
                'sort_order' => 11,
                'field_options' => [
                    'options' => [
                        'en' => 'English',
                        'es' => 'Spanish',
                        'fr' => 'French',
                        'de' => 'German',
                        'pt' => 'Portuguese',
                        'it' => 'Italian',
                        'ar' => 'Arabic',
                        'zh' => 'Chinese',
                        'ja' => 'Japanese',
                        'ko' => 'Korean',
                    ],
                ],
            ],
        ];

        foreach ($userFields as $fieldData) {
            $usersCollection->allFields()->create($fieldData);
        }

        // Create a sample Posts collection
        $postsCollection = DataCollection::create([
            'name' => 'Posts',
            'slug' => 'posts',
            'description' => 'Blog posts and articles collection',
            'icon' => 'document-text',
            'is_system' => false,
            'is_active' => true,
            'sort_order' => 2,
            'settings' => [
                'versioning' => false,
                'auto_publish' => false,
                'comments_enabled' => true,
            ],
            'permissions' => [
                'create' => ['user', 'admin'],
                'read' => ['*'],
                'update' => ['owner', 'admin'],
                'delete' => ['owner', 'admin'],
            ],
        ]);

        $postFields = [
            [
                'name' => 'title',
                'label' => 'Post Title',
                'type' => 'text',
                'is_required' => true,
                'is_searchable' => true,
                'sort_order' => 1,
                'validation_rules' => ['min:3', 'max:200'],
            ],
            [
                'name' => 'slug',
                'label' => 'URL Slug',
                'type' => 'text',
                'is_required' => false,
                'is_unique' => true,
                'sort_order' => 2,
                'validation_rules' => ['regex:/^[a-z0-9-]+$/'],
            ],
            [
                'name' => 'content',
                'label' => 'Content',
                'type' => 'textarea',
                'is_required' => true,
                'is_searchable' => true,
                'sort_order' => 3,
                'validation_rules' => ['min:10'],
            ],
            [
                'name' => 'excerpt',
                'label' => 'Excerpt',
                'type' => 'textarea',
                'is_required' => false,
                'sort_order' => 4,
                'validation_rules' => ['max:300'],
            ],
            [
                'name' => 'featured_image',
                'label' => 'Featured Image',
                'type' => 'image',
                'is_required' => false,
                'sort_order' => 5,
                'field_options' => [
                    'allowed_types' => ['jpg', 'jpeg', 'png', 'webp'],
                    'max_size' => 5120,
                    'path' => 'posts/images',
                ],
            ],
            [
                'name' => 'status',
                'label' => 'Publication Status',
                'type' => 'select',
                'default_value' => 'draft',
                'is_required' => true,
                'sort_order' => 6,
                'field_options' => [
                    'options' => [
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'scheduled' => 'Scheduled',
                        'archived' => 'Archived',
                    ],
                ],
            ],
            [
                'name' => 'published_at',
                'label' => 'Publish Date',
                'type' => 'datetime',
                'is_required' => false,
                'sort_order' => 7,
            ],
            [
                'name' => 'author',
                'label' => 'Author',
                'type' => 'relation',
                'is_required' => true,
                'sort_order' => 8,
                'related_collection_id' => $usersCollection->id,
            ],
            [
                'name' => 'tags',
                'label' => 'Tags',
                'type' => 'multiselect',
                'is_required' => false,
                'sort_order' => 9,
                'field_options' => [
                    'options' => [
                        'technology' => 'Technology',
                        'business' => 'Business',
                        'lifestyle' => 'Lifestyle',
                        'health' => 'Health',
                        'education' => 'Education',
                        'entertainment' => 'Entertainment',
                    ],
                ],
            ],
            [
                'name' => 'meta_description',
                'label' => 'Meta Description',
                'type' => 'textarea',
                'is_required' => false,
                'sort_order' => 10,
                'validation_rules' => ['max:160'],
                'ui_settings' => [
                    'group' => 'SEO',
                ],
            ],
        ];

        foreach ($postFields as $fieldData) {
            $postsCollection->allFields()->create($fieldData);
        }

        $this->command->info('Data collections seeded successfully!');
        $this->command->info('Created collections:');
        $this->command->info('- Users (system collection with ' . $usersCollection->allFields()->count() . ' fields)');
        $this->command->info('- Posts (sample collection with ' . $postsCollection->allFields()->count() . ' fields)');
    }
}