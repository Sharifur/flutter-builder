<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\IconField;
use Plugins\Builder\Fields\NumberField;

class StatCardComponent extends BaseComponent
{
    protected string $name = 'Stat Card';
    protected string $type = 'StatCard';
    protected string $category = 'dashboard';
    protected string $description = 'Statistics card with icon, value and change indicator';
    protected ?string $icon = 'bar-chart';
    protected int $sortOrder = 40;

    protected array $defaultConfig = [
        'title' => 'Total Customers',
        'value' => '32,502',
        'change' => '2.1%',
        'changeType' => 'decrease',
        'icon' => 'users',
        'iconColor' => '#6366F1',
        'backgroundColor' => '#FFFFFF',
        'borderRadius' => 12,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('title', 'Title', 'Total Customers', true)
                ->placeholder('Enter stat title')
                ->maxLength(40),
            TextField::create('value', 'Value', '32,502', true)
                ->placeholder('Enter value')
                ->helpText('Main statistic value'),
            TextField::create('change', 'Change', '2.1%', false)
                ->placeholder('0.0%')
                ->helpText('Change percentage or amount'),
            SelectField::create('changeType', 'Change Type', [
                'increase' => 'Increase (Green)',
                'decrease' => 'Decrease (Red)',
                'neutral' => 'Neutral (Gray)',
            ], 'increase'),
            IconField::create('icon', 'Icon', 'users')
                ->helpText('Select an icon for the stat'),
            ColorField::create('iconColor', 'Icon Color', '#6366F1')
                ->helpText('Color for the icon'),
            ColorField::create('backgroundColor', 'Background Color', '#FFFFFF')
                ->helpText('Card background color'),
            NumberField::create('borderRadius', 'Border Radius', 12, false, 0, 30)
                ->helpText('Card corner rounding'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'StatCard',
            'data' => [
                'title' => $mergedConfig['title'],
                'value' => $mergedConfig['value'],
                'change' => $mergedConfig['change'],
                'changeType' => $mergedConfig['changeType'],
                'icon' => $mergedConfig['icon'],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                    'iconColor' => $mergedConfig['iconColor'],
                    'titleStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w500',
                        'color' => '#6B7280',
                    ],
                    'valueStyle' => [
                        'fontSize' => 24.0,
                        'fontWeight' => 'w700',
                        'color' => '#1F2937',
                    ],
                    'changeStyle' => [
                        'fontSize' => 12.0,
                        'fontWeight' => 'w500',
                        'color' => $this->getChangeColor($mergedConfig['changeType']),
                    ],
                ],
                'iconSize' => 24.0,
                'padding' => 20.0,
                'elevation' => 1.0,
            ],
        ];
    }

    private function getChangeColor(string $changeType): string
    {
        return match ($changeType) {
            'increase' => '#10B981',
            'decrease' => '#EF4444',
            'neutral' => '#6B7280',
            default => '#6B7280',
        };
    }
}