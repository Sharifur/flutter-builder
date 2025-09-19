<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class CheckboxComponent extends BaseComponent
{
    protected string $name = 'Checkbox';
    protected string $type = 'Checkbox';
    protected string $category = 'forms';
    protected string $description = 'Checkbox input with customizable styling and states';
    protected ?string $icon = 'check-box';
    protected int $sortOrder = 61;

    protected array $defaultConfig = [
        'value' => false,
        'tristate' => false,
        'label' => 'Accept terms and conditions',
        'showLabel' => true,
        'labelPosition' => 'right',
        'activeColor' => '#3B82F6',
        'checkColor' => '#FFFFFF',
        'focusColor' => '#3B82F6',
        'hoverColor' => '#DBEAFE',
        'overlayColor' => '#3B82F6',
        'splashRadius' => 20,
        'materialTapTargetSize' => 'padded',
        'visualDensity' => 'standard',
        'shape' => 'square',
        'side' => [
            'color' => '#D1D5DB',
            'width' => 2,
        ],
        'disabled' => false,
        'autofocus' => false,
        'semanticLabel' => '',
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            BooleanField::create('value', 'Default Value', false)
                ->helpText('Initial checkbox state'),
            BooleanField::create('tristate', 'Three State', false)
                ->helpText('Allow null/indeterminate state'),
            TextField::create('label', 'Label', 'Accept terms and conditions', false)
                ->placeholder('Enter checkbox label')
                ->maxLength(200)
                ->helpText('Label text for the checkbox'),
            BooleanField::create('showLabel', 'Show Label', true)
                ->helpText('Display label next to checkbox'),
            SelectField::create('labelPosition', 'Label Position', [
                'left' => 'Left of checkbox',
                'right' => 'Right of checkbox',
                'top' => 'Above checkbox',
                'bottom' => 'Below checkbox',
            ], 'right')
                ->helpText('Position of the label'),
            ColorField::create('activeColor', 'Active Color', '#3B82F6')
                ->helpText('Color when checkbox is checked'),
            ColorField::create('checkColor', 'Check Color', '#FFFFFF')
                ->helpText('Color of the checkmark'),
            ColorField::create('focusColor', 'Focus Color', '#3B82F6')
                ->helpText('Color when focused'),
            ColorField::create('hoverColor', 'Hover Color', '#DBEAFE')
                ->helpText('Color when hovered'),
            NumberField::create('splashRadius', 'Splash Radius', 20, false, 10, 50)
                ->helpText('Radius of the tap splash effect'),
            SelectField::create('materialTapTargetSize', 'Tap Target Size', [
                'padded' => 'Padded (48dp minimum)',
                'shrinkWrap' => 'Shrink Wrap (actual size)',
            ], 'padded')
                ->helpText('Size of the touch target'),
            SelectField::create('visualDensity', 'Visual Density', [
                'standard' => 'Standard',
                'comfortable' => 'Comfortable',
                'compact' => 'Compact',
            ], 'standard')
                ->helpText('Visual density of the checkbox'),
            SelectField::create('shape', 'Shape', [
                'square' => 'Square',
                'circle' => 'Circle',
                'rounded' => 'Rounded Square',
            ], 'square')
                ->helpText('Shape of the checkbox'),
            ColorField::create('borderColor', 'Border Color', '#D1D5DB')
                ->helpText('Color of the checkbox border'),
            NumberField::create('borderWidth', 'Border Width', 2, false, 1, 5)
                ->helpText('Width of the checkbox border'),
            BooleanField::create('disabled', 'Disabled', false)
                ->helpText('Disable the checkbox'),
            BooleanField::create('autofocus', 'Auto Focus', false)
                ->helpText('Automatically focus when rendered'),
            TextField::create('semanticLabel', 'Semantic Label', '', false)
                ->placeholder('Accessibility label')
                ->maxLength(100)
                ->helpText('Label for screen readers'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'Checkbox',
            'data' => [
                'value' => $mergedConfig['value'],
                'tristate' => $mergedConfig['tristate'],
                'label' => $mergedConfig['label'],
                'showLabel' => $mergedConfig['showLabel'],
                'labelPosition' => $mergedConfig['labelPosition'],
                'disabled' => $mergedConfig['disabled'],
                'autofocus' => $mergedConfig['autofocus'],
                'semanticLabel' => $mergedConfig['semanticLabel'] ?: $mergedConfig['label'],
                'style' => [
                    'activeColor' => $mergedConfig['activeColor'],
                    'checkColor' => $mergedConfig['checkColor'],
                    'focusColor' => $mergedConfig['focusColor'],
                    'hoverColor' => $mergedConfig['hoverColor'],
                    'overlayColor' => $mergedConfig['overlayColor'],
                    'splashRadius' => (float) $mergedConfig['splashRadius'],
                    'materialTapTargetSize' => $mergedConfig['materialTapTargetSize'],
                    'visualDensity' => $mergedConfig['visualDensity'],
                    'shape' => $mergedConfig['shape'],
                    'side' => [
                        'color' => $mergedConfig['borderColor'] ?? $mergedConfig['side']['color'],
                        'width' => (float) ($mergedConfig['borderWidth'] ?? $mergedConfig['side']['width']),
                    ],
                    'labelStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['disabled'] ? '#9CA3AF' : '#374151',
                    ],
                ],
                'constraints' => [
                    'minWidth' => 18.0,
                    'minHeight' => 18.0,
                ],
                'animation' => [
                    'duration' => 150,
                    'curve' => 'easeInOut',
                ],
            ],
        ];
    }
}