<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class TextComponent extends BaseComponent
{
    protected string $name = 'Text';
    protected string $type = 'Text';
    protected string $category = 'basic';
    protected string $description = 'Display text with customizable styling';
    protected ?string $icon = 'type';
    protected int $sortOrder = 1;

    protected array $defaultConfig = [
        'value' => 'Welcome to your app',
        'color' => '#1F2937',
        'fontSize' => 18,
        'fontWeight' => '600',
        'textAlign' => 'left',
    ];

    protected array $fieldDefinitions = [
        // This will be auto-generated based on getFieldDefinitions method
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('value', 'Text Content', 'Welcome to your app', true)
                ->placeholder('Enter your text here')
                ->maxLength(500),
            ColorField::create('color', 'Text Color', '#1F2937')
                ->helpText('Choose the text color'),
            NumberField::create('fontSize', 'Font Size', 18, false, 8, 72)
                ->helpText('Size in pixels'),
            SelectField::create('fontWeight', 'Font Weight', [
                '400' => 'Normal',
                '500' => 'Medium',
                '600' => 'Semi Bold',
                '700' => 'Bold',
                '800' => 'Extra Bold',
            ], '600'),
            SelectField::create('textAlign', 'Text Alignment', [
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ], 'left'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'Text',
            'data' => [
                'text' => $mergedConfig['value'],
                'style' => [
                    'color' => $mergedConfig['color'],
                    'fontSize' => (float) $mergedConfig['fontSize'],
                    'fontWeight' => $this->mapFontWeight($mergedConfig['fontWeight']),
                    'textAlign' => $this->mapTextAlign($mergedConfig['textAlign']),
                ],
            ],
        ];
    }

    private function mapFontWeight(string $weight): string
    {
        return match ($weight) {
            '400' => 'normal',
            '500' => 'w500',
            '600' => 'w600',
            '700' => 'bold',
            '800' => 'w800',
            default => 'w600',
        };
    }

    private function mapTextAlign(string $align): string
    {
        return match ($align) {
            'left' => 'start',
            'center' => 'center',
            'right' => 'end',
            default => 'start',
        };
    }
}