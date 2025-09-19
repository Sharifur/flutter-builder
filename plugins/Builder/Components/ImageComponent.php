<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class ImageComponent extends BaseComponent
{
    protected string $name = 'Image';
    protected string $type = 'Image';
    protected string $category = 'basic';
    protected string $description = 'Display images with customizable styling and fit options';
    protected ?string $icon = 'image';
    protected int $sortOrder = 3;

    protected array $defaultConfig = [
        'url' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=300&h=200&fit=crop',
        'width' => 300,
        'height' => 200,
        'alt' => 'Beautiful image',
        'borderRadius' => 8,
        'fit' => 'cover',
    ];

    public function getFieldDefinitions(): array
    {
        return [
            TextField::create('url', 'Image URL', 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=300&h=200&fit=crop', true, 'Enter image URL'),
            NumberField::create('width', 'Width', 300, false, 50, 800, 'Width in pixels'),
            NumberField::create('height', 'Height', 200, false, 50, 600, 'Height in pixels'),
            TextField::create('alt', 'Alt Text', 'Beautiful image', false, 'Accessibility description'),
            NumberField::create('borderRadius', 'Border Radius', 8, false, 0, 50, 'Rounded corners'),
            SelectField::create('fit', 'Image Fit', [
                'cover' => 'Cover',
                'contain' => 'Contain',
                'fill' => 'Fill',
                'fitWidth' => 'Fit Width',
                'fitHeight' => 'Fit Height',
            ], 'cover'),
        ];
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'Image',
            'data' => [
                'url' => $mergedConfig['url'],
                'width' => (float) $mergedConfig['width'],
                'height' => (float) $mergedConfig['height'],
                'alt' => $mergedConfig['alt'],
                'fit' => $this->mapImageFit($mergedConfig['fit']),
                'borderRadius' => (float) $mergedConfig['borderRadius'],
            ],
        ];
    }

    private function mapImageFit(string $fit): string
    {
        return match ($fit) {
            'cover' => 'BoxFit.cover',
            'contain' => 'BoxFit.contain',
            'fill' => 'BoxFit.fill',
            'fitWidth' => 'BoxFit.fitWidth',
            'fitHeight' => 'BoxFit.fitHeight',
            default => 'BoxFit.cover',
        };
    }
}