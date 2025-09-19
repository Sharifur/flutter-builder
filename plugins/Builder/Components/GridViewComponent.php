<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\ColorField;

class GridViewComponent extends BaseComponent
{
    protected string $name = 'Grid View';
    protected string $type = 'GridView';
    protected string $category = 'layout';
    protected string $description = 'Grid layout with customizable columns and spacing';
    protected ?string $icon = 'grid-3x3';
    protected int $sortOrder = 51;

    protected array $defaultConfig = [
        'crossAxisCount' => 2,
        'mainAxisSpacing' => 10,
        'crossAxisSpacing' => 10,
        'childAspectRatio' => 1.0,
        'itemCount' => 6,
        'shrinkWrap' => false,
        'physics' => 'bouncing',
        'backgroundColor' => '#F9FAFB',
        'itemBackgroundColor' => '#FFFFFF',
        'borderRadius' => 8,
        'elevation' => 2,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            NumberField::create('crossAxisCount', 'Columns', 2, true, 1, 6)
                ->helpText('Number of columns in the grid'),
            NumberField::create('mainAxisSpacing', 'Main Axis Spacing', 10, false, 0, 50)
                ->helpText('Spacing between rows'),
            NumberField::create('crossAxisSpacing', 'Cross Axis Spacing', 10, false, 0, 50)
                ->helpText('Spacing between columns'),
            NumberField::create('childAspectRatio', 'Aspect Ratio', 1.0, false, 0.1, 5.0)
                ->step(0.1)
                ->helpText('Width/height ratio of items'),
            NumberField::create('itemCount', 'Item Count', 6, true, 1, 50)
                ->helpText('Number of items in the grid'),
            BooleanField::create('shrinkWrap', 'Shrink Wrap', false)
                ->helpText('Take only required space'),
            SelectField::create('physics', 'Scroll Physics', [
                'bouncing' => 'Bouncing (iOS style)',
                'clamping' => 'Clamping (Android style)',
                'never' => 'Never Scrollable',
            ], 'bouncing')
                ->helpText('Scroll behavior'),
            ColorField::create('backgroundColor', 'Background Color', '#F9FAFB')
                ->helpText('Grid background color'),
            ColorField::create('itemBackgroundColor', 'Item Background', '#FFFFFF')
                ->helpText('Individual item background'),
            NumberField::create('borderRadius', 'Border Radius', 8, false, 0, 30)
                ->helpText('Corner rounding for items'),
            NumberField::create('elevation', 'Elevation', 2, false, 0, 10)
                ->helpText('Shadow depth for items'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'GridView',
            'data' => [
                'crossAxisCount' => (int) $mergedConfig['crossAxisCount'],
                'mainAxisSpacing' => (float) $mergedConfig['mainAxisSpacing'],
                'crossAxisSpacing' => (float) $mergedConfig['crossAxisSpacing'],
                'childAspectRatio' => (float) $mergedConfig['childAspectRatio'],
                'itemCount' => (int) $mergedConfig['itemCount'],
                'shrinkWrap' => $mergedConfig['shrinkWrap'],
                'physics' => $mergedConfig['physics'],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'itemBackgroundColor' => $mergedConfig['itemBackgroundColor'],
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                    'elevation' => (float) $mergedConfig['elevation'],
                ],
                'items' => $this->generateSampleItems((int) $mergedConfig['itemCount']),
            ],
        ];
    }

    private function generateSampleItems(int $count): array
    {
        $items = [];
        $icons = ['star', 'heart', 'home', 'settings', 'search', 'favorite', 'add', 'share'];
        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57', '#FF9FF3', '#54A0FF', '#5F27CD'];

        for ($i = 1; $i <= $count; $i++) {
            $items[] = [
                'id' => $i,
                'title' => "Item $i",
                'icon' => $icons[($i - 1) % count($icons)],
                'color' => $colors[($i - 1) % count($colors)],
            ];
        }
        return $items;
    }
}