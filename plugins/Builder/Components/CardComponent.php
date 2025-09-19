<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\SelectField;

class CardComponent extends BaseComponent
{
    protected string $name = 'Card';
    protected string $type = 'Card';
    protected string $category = 'basic';
    protected string $description = 'Card container with title, subtitle and shadow';
    protected ?string $icon = 'card';
    protected int $sortOrder = 6;

    protected array $defaultConfig = [
        'title' => 'Card Title',
        'subtitle' => 'Card subtitle',
        'backgroundColor' => '#FFFFFF',
        'borderRadius' => 12,
        'shadow' => true,
        'padding' => 16,
        'elevation' => 2,
    ];

    public function getFieldDefinitions(): array
    {
        return [
            TextField::create('title', 'Title', 'Card Title', true, 'Card title text'),
            TextField::create('subtitle', 'Subtitle', 'Card subtitle', false, 'Card subtitle text'),
            ColorField::create('backgroundColor', 'Background Color', '#FFFFFF'),
            NumberField::create('borderRadius', 'Border Radius', 12, false, 0, 50, 'Rounded corners'),
            NumberField::create('padding', 'Padding', 16, false, 0, 50, 'Internal padding'),
            SelectField::create('shadow', 'Shadow', [
                'true' => 'With Shadow',
                'false' => 'No Shadow',
            ], 'true'),
            NumberField::create('elevation', 'Elevation', 2, false, 0, 10, 'Shadow elevation level'),
        ];
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'Card',
            'data' => [
                'title' => $mergedConfig['title'],
                'subtitle' => $mergedConfig['subtitle'],
                'decoration' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                ],
                'padding' => (float) $mergedConfig['padding'],
                'elevation' => $mergedConfig['shadow'] === 'true' || $mergedConfig['shadow'] === true
                    ? (float) $mergedConfig['elevation']
                    : 0,
                'style' => [
                    'titleStyle' => [
                        'fontSize' => 18.0,
                        'fontWeight' => 'w600',
                        'color' => '#1F2937',
                    ],
                    'subtitleStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w400',
                        'color' => '#6B7280',
                    ],
                ],
            ],
        ];
    }
}