<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\ArrayField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\BooleanField;

class TabBarComponent extends BaseComponent
{
    protected string $name = 'Tab Bar';
    protected string $type = 'TabBar';
    protected string $category = 'navigation';
    protected string $description = 'Navigation tab bar with customizable tabs';
    protected ?string $icon = 'menu';
    protected int $sortOrder = 30;

    protected array $defaultConfig = [
        'tabs' => ['Dashboard', 'Cards', 'Accounts', 'Settings'],
        'activeTab' => 0,
        'backgroundColor' => '#FFFFFF',
        'activeColor' => '#6366F1',
        'inactiveColor' => '#9CA3AF',
        'showIcons' => false,
        'tabHeight' => 48,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            ArrayField::create('tabs', 'Tab Labels', ['Dashboard', 'Cards', 'Accounts', 'Settings'], true)
                ->itemType('text')
                ->minItems(2)
                ->maxItems(6)
                ->helpText('Tab names (2-6 tabs)'),
            NumberField::create('activeTab', 'Active Tab Index', 0, false, 0, 5)
                ->helpText('Which tab is selected (0-based index)'),
            ColorField::create('backgroundColor', 'Background Color', '#FFFFFF')
                ->helpText('Tab bar background'),
            ColorField::create('activeColor', 'Active Color', '#6366F1')
                ->helpText('Color for active tab'),
            ColorField::create('inactiveColor', 'Inactive Color', '#9CA3AF')
                ->helpText('Color for inactive tabs'),
            BooleanField::create('showIcons', 'Show Icons', false)
                ->helpText('Display icons with tab labels'),
            NumberField::create('tabHeight', 'Tab Height', 48, false, 32, 80)
                ->helpText('Height of tab bar in pixels'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        // Ensure activeTab is within bounds
        $activeTab = min(max(0, (int) $mergedConfig['activeTab']), count($mergedConfig['tabs']) - 1);

        return [
            'type' => 'TabBar',
            'data' => [
                'tabs' => $mergedConfig['tabs'],
                'activeTab' => $activeTab,
                'showIcons' => $mergedConfig['showIcons'],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'activeColor' => $mergedConfig['activeColor'],
                    'inactiveColor' => $mergedConfig['inactiveColor'],
                    'height' => (float) $mergedConfig['tabHeight'],
                    'activeTextStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w600',
                        'color' => $mergedConfig['activeColor'],
                    ],
                    'inactiveTextStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['inactiveColor'],
                    ],
                ],
                'borderRadius' => 0.0,
                'elevation' => 2.0,
            ],
        ];
    }
}