<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\ArrayField;

class ListViewComponent extends BaseComponent
{
    protected string $name = 'List View';
    protected string $type = 'ListView';
    protected string $category = 'layout';
    protected string $description = 'Scrollable list of items with customizable appearance';
    protected ?string $icon = 'list';
    protected int $sortOrder = 50;

    protected array $defaultConfig = [
        'scrollDirection' => 'vertical',
        'itemCount' => 5,
        'shrinkWrap' => false,
        'physics' => 'bouncing',
        'itemHeight' => 60,
        'dividerHeight' => 1,
        'showDividers' => true,
        'dividerColor' => '#E5E7EB',
        'backgroundColor' => '#FFFFFF',
        'padding' => [
            'top' => 16,
            'bottom' => 16,
            'left' => 16,
            'right' => 16,
        ],
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            SelectField::create('scrollDirection', 'Scroll Direction', [
                'vertical' => 'Vertical',
                'horizontal' => 'Horizontal',
            ], 'vertical')
                ->helpText('List scroll direction'),
            NumberField::create('itemCount', 'Item Count', 5, true, 1, 100)
                ->helpText('Number of items in the list'),
            BooleanField::create('shrinkWrap', 'Shrink Wrap', false)
                ->helpText('Take only required space'),
            SelectField::create('physics', 'Scroll Physics', [
                'bouncing' => 'Bouncing (iOS style)',
                'clamping' => 'Clamping (Android style)',
                'never' => 'Never Scrollable',
                'always' => 'Always Scrollable',
            ], 'bouncing')
                ->helpText('Scroll behavior'),
            NumberField::create('itemHeight', 'Item Height', 60, false, 20, 200)
                ->helpText('Height of each list item'),
            BooleanField::create('showDividers', 'Show Dividers', true)
                ->helpText('Show dividers between items'),
            NumberField::create('dividerHeight', 'Divider Height', 1, false, 0, 10)
                ->helpText('Height of dividers in pixels'),
            TextField::create('dividerColor', 'Divider Color', '#E5E7EB')
                ->helpText('Color of dividers'),
            TextField::create('backgroundColor', 'Background Color', '#FFFFFF')
                ->helpText('List background color'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'ListView',
            'data' => [
                'scrollDirection' => $mergedConfig['scrollDirection'],
                'itemCount' => (int) $mergedConfig['itemCount'],
                'shrinkWrap' => $mergedConfig['shrinkWrap'],
                'physics' => $mergedConfig['physics'],
                'showDividers' => $mergedConfig['showDividers'],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'itemHeight' => (float) $mergedConfig['itemHeight'],
                    'dividerHeight' => (float) $mergedConfig['dividerHeight'],
                    'dividerColor' => $mergedConfig['dividerColor'],
                    'padding' => [
                        'top' => (float) $mergedConfig['padding']['top'],
                        'bottom' => (float) $mergedConfig['padding']['bottom'],
                        'left' => (float) $mergedConfig['padding']['left'],
                        'right' => (float) $mergedConfig['padding']['right'],
                    ],
                ],
                'items' => $this->generateSampleItems((int) $mergedConfig['itemCount']),
            ],
        ];
    }

    private function generateSampleItems(int $count): array
    {
        $items = [];
        for ($i = 1; $i <= $count; $i++) {
            $items[] = [
                'id' => $i,
                'title' => "Item $i",
                'subtitle' => "Description for item $i",
                'icon' => 'star',
                'trailing' => 'chevron_right',
            ];
        }
        return $items;
    }
}