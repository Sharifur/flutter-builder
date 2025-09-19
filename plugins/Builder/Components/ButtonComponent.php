<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class ButtonComponent extends BaseComponent
{
    protected string $name = 'Button';
    protected string $type = 'Button';
    protected string $category = 'basic';
    protected string $description = 'Interactive button with customizable styling';
    protected ?string $icon = 'square';
    protected int $sortOrder = 2;

    protected array $defaultConfig = [
        'label' => 'Get Started',
        'color' => '#3B82F6',
        'textColor' => '#FFFFFF',
        'action' => null,
        'borderRadius' => 8,
        'size' => 'medium',
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('label', 'Button Text', 'Get Started', true)
                ->placeholder('Enter button label')
                ->maxLength(50),
            ColorField::create('color', 'Background Color', '#3B82F6')
                ->helpText('Button background color'),
            ColorField::create('textColor', 'Text Color', '#FFFFFF')
                ->helpText('Button text color'),
            NumberField::create('borderRadius', 'Border Radius', 8, false, 0, 50)
                ->helpText('Rounded corners in pixels'),
            SelectField::create('size', 'Button Size', [
                'small' => 'Small',
                'medium' => 'Medium',
                'large' => 'Large',
            ], 'medium'),
            TextField::create('action', 'Action', '', false)
                ->placeholder('Optional action (e.g., navigate, submit)')
                ->helpText('Define button behavior'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'ElevatedButton',
            'data' => [
                'text' => $mergedConfig['label'],
                'style' => [
                    'backgroundColor' => $mergedConfig['color'],
                    'foregroundColor' => $mergedConfig['textColor'],
                    'shape' => [
                        'type' => 'RoundedRectangleBorder',
                        'borderRadius' => (float) $mergedConfig['borderRadius'],
                    ],
                    'padding' => $this->getSizePadding($mergedConfig['size']),
                ],
                'action' => $mergedConfig['action'],
            ],
        ];
    }

    private function getSizePadding(string $size): array
    {
        return match ($size) {
            'small' => ['horizontal' => 12.0, 'vertical' => 8.0],
            'medium' => ['horizontal' => 16.0, 'vertical' => 12.0],
            'large' => ['horizontal' => 20.0, 'vertical' => 16.0],
            default => ['horizontal' => 16.0, 'vertical' => 12.0],
        };
    }
}