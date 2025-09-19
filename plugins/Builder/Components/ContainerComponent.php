<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\ColorField;

class ContainerComponent extends BaseComponent
{
    protected string $name = 'Container';
    protected string $type = 'Container';
    protected string $category = 'basic';
    protected string $description = 'Layout container for organizing child widgets';
    protected ?string $icon = 'container';
    protected int $sortOrder = 5;

    protected array $defaultConfig = [
        'direction' => 'column',
        'spacing' => 12,
        'padding' => 20,
        'backgroundColor' => '#F9FAFB',
        'borderRadius' => 12,
        'alignment' => 'start',
        'crossAlignment' => 'start',
    ];

    public function getFieldDefinitions(): array
    {
        return [
            SelectField::create('direction', 'Direction', [
                'column' => 'Column (Vertical)',
                'row' => 'Row (Horizontal)',
            ], 'column'),
            NumberField::create('spacing', 'Spacing', 12, false, 0, 50, 'Space between children'),
            NumberField::create('padding', 'Padding', 20, false, 0, 100, 'Internal padding'),
            ColorField::create('backgroundColor', 'Background Color', '#F9FAFB'),
            NumberField::create('borderRadius', 'Border Radius', 12, false, 0, 50, 'Rounded corners'),
            SelectField::create('alignment', 'Main Alignment', [
                'start' => 'Start',
                'center' => 'Center',
                'end' => 'End',
                'spaceBetween' => 'Space Between',
                'spaceAround' => 'Space Around',
                'spaceEvenly' => 'Space Evenly',
            ], 'start'),
            SelectField::create('crossAlignment', 'Cross Alignment', [
                'start' => 'Start',
                'center' => 'Center',
                'end' => 'End',
                'stretch' => 'Stretch',
            ], 'start'),
        ];
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => $mergedConfig['direction'] === 'row' ? 'Row' : 'Column',
            'data' => [
                'mainAxisAlignment' => $this->mapMainAlignment($mergedConfig['alignment']),
                'crossAxisAlignment' => $this->mapCrossAlignment($mergedConfig['crossAlignment']),
                'spacing' => (float) $mergedConfig['spacing'],
                'padding' => (float) $mergedConfig['padding'],
                'decoration' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                ],
                'children' => [], // Will be populated with child widgets
            ],
        ];
    }

    private function mapMainAlignment(string $alignment): string
    {
        return match ($alignment) {
            'start' => 'MainAxisAlignment.start',
            'center' => 'MainAxisAlignment.center',
            'end' => 'MainAxisAlignment.end',
            'spaceBetween' => 'MainAxisAlignment.spaceBetween',
            'spaceAround' => 'MainAxisAlignment.spaceAround',
            'spaceEvenly' => 'MainAxisAlignment.spaceEvenly',
            default => 'MainAxisAlignment.start',
        };
    }

    private function mapCrossAlignment(string $alignment): string
    {
        return match ($alignment) {
            'start' => 'CrossAxisAlignment.start',
            'center' => 'CrossAxisAlignment.center',
            'end' => 'CrossAxisAlignment.end',
            'stretch' => 'CrossAxisAlignment.stretch',
            default => 'CrossAxisAlignment.start',
        };
    }
}