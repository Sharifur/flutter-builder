<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo user
        $user = \App\Models\User::firstOrCreate([
            'email' => 'demo@example.com'
        ], [
            'name' => 'Demo User',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Create a demo project
        $project = $user->projects()->firstOrCreate([
            'name' => 'Sample Flutter App'
        ], [
            'description' => 'A sample Flutter app created with FlutterBuilder to demonstrate features'
        ]);

        // Create Home page
        $homePage = $project->appPages()->firstOrCreate([
            'slug' => 'home'
        ], [
            'name' => 'Home'
        ]);

        // Create About page
        $aboutPage = $project->appPages()->firstOrCreate([
            'slug' => 'about'
        ], [
            'name' => 'About'
        ]);

        // Add widgets to Home page
        if ($homePage->widgets()->count() === 0) {
            $homePage->widgets()->createMany([
                [
                    'type' => 'Text',
                    'config' => [
                        'value' => 'Welcome to FlutterBuilder!',
                        'color' => '#2563EB',
                        'fontSize' => 24,
                        'fontWeight' => 'bold'
                    ],
                    'order' => 1
                ],
                [
                    'type' => 'Text',
                    'config' => [
                        'value' => 'This is a sample app created with our drag-and-drop builder.',
                        'color' => '#6B7280',
                        'fontSize' => 16,
                        'fontWeight' => 'normal'
                    ],
                    'order' => 2
                ],
                [
                    'type' => 'Button',
                    'config' => [
                        'label' => 'Go to About Page',
                        'color' => '#10B981',
                        'textColor' => '#FFFFFF',
                        'action' => 'goTo:about'
                    ],
                    'order' => 3
                ],
                [
                    'type' => 'Image',
                    'config' => [
                        'url' => 'https://via.placeholder.com/300x200/3B82F6/FFFFFF?text=FlutterBuilder',
                        'width' => 300,
                        'height' => 200,
                        'alt' => 'FlutterBuilder Demo'
                    ],
                    'order' => 4
                ],
                [
                    'type' => 'Input',
                    'config' => [
                        'placeholder' => 'Enter your name',
                        'label' => 'Your Name',
                        'required' => false,
                        'type' => 'text'
                    ],
                    'order' => 5
                ]
            ]);
        }

        // Add widgets to About page
        if ($aboutPage->widgets()->count() === 0) {
            $aboutPage->widgets()->createMany([
                [
                    'type' => 'Text',
                    'config' => [
                        'value' => 'About FlutterBuilder',
                        'color' => '#1F2937',
                        'fontSize' => 20,
                        'fontWeight' => 'bold'
                    ],
                    'order' => 1
                ],
                [
                    'type' => 'Text',
                    'config' => [
                        'value' => 'FlutterBuilder is a powerful drag-and-drop tool for creating Flutter applications without coding.',
                        'color' => '#4B5563',
                        'fontSize' => 14,
                        'fontWeight' => 'normal'
                    ],
                    'order' => 2
                ],
                [
                    'type' => 'Container',
                    'config' => [
                        'direction' => 'column',
                        'spacing' => 8,
                        'padding' => 16,
                        'backgroundColor' => '#F3F4F6',
                        'children' => [
                            [
                                'type' => 'Text',
                                'value' => 'Features:',
                                'color' => '#374151',
                                'fontSize' => 16,
                                'fontWeight' => 'bold'
                            ],
                            [
                                'type' => 'Text',
                                'value' => '• Drag and drop interface',
                                'color' => '#6B7280',
                                'fontSize' => 14,
                                'fontWeight' => 'normal'
                            ],
                            [
                                'type' => 'Text',
                                'value' => '• Real-time preview',
                                'color' => '#6B7280',
                                'fontSize' => 14,
                                'fontWeight' => 'normal'
                            ],
                            [
                                'type' => 'Text',
                                'value' => '• Export to Flutter project',
                                'color' => '#6B7280',
                                'fontSize' => 14,
                                'fontWeight' => 'normal'
                            ]
                        ]
                    ],
                    'order' => 3
                ],
                [
                    'type' => 'Button',
                    'config' => [
                        'label' => 'Back to Home',
                        'color' => '#6366F1',
                        'textColor' => '#FFFFFF',
                        'action' => 'goTo:home'
                    ],
                    'order' => 4
                ]
            ]);
        }

        $this->command->info('Demo project created successfully!');
        $this->command->info('User: demo@example.com / password: password');
        $this->command->info('Project ID: ' . $project->id);
    }
}
