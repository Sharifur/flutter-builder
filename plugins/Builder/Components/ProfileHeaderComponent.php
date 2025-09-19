<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\NumberField;

class ProfileHeaderComponent extends BaseComponent
{
    protected string $name = 'Profile Header';
    protected string $type = 'ProfileHeader';
    protected string $category = 'social';
    protected string $description = 'User profile header with avatar and name';
    protected ?string $icon = 'user';
    protected int $sortOrder = 10;

    protected array $defaultConfig = [
        'name' => 'John Doe',
        'avatar' => 'https://via.placeholder.com/60x60',
        'backgroundColor' => '#6366F1',
        'textColor' => '#FFFFFF',
        'avatarSize' => 60,
        'nameSize' => 20,
    ];

    public function getFieldDefinitions(): array
    {
        return [
            TextField::create('name', 'Name', 'John Doe', true, 'User display name'),
            TextField::create('avatar', 'Avatar URL', 'https://via.placeholder.com/60x60', false, 'Profile picture URL'),
            ColorField::create('backgroundColor', 'Background Color', '#6366F1'),
            ColorField::create('textColor', 'Text Color', '#FFFFFF'),
            NumberField::create('avatarSize', 'Avatar Size', 60, false, 30, 120, 'Avatar diameter in pixels'),
            NumberField::create('nameSize', 'Name Font Size', 20, false, 12, 32, 'Name text size'),
        ];
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'ProfileHeader',
            'data' => [
                'name' => $mergedConfig['name'],
                'avatar' => $mergedConfig['avatar'],
                'avatarSize' => (float) $mergedConfig['avatarSize'],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'textColor' => $mergedConfig['textColor'],
                    'nameStyle' => [
                        'fontSize' => (float) $mergedConfig['nameSize'],
                        'fontWeight' => 'w600',
                        'color' => $mergedConfig['textColor'],
                    ],
                ],
                'padding' => 20.0,
                'borderRadius' => 12.0,
            ],
        ];
    }
}