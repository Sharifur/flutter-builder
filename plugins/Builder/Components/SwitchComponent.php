<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class SwitchComponent extends BaseComponent
{
    protected string $name = 'Switch';
    protected string $type = 'Switch';
    protected string $category = 'forms';
    protected string $description = 'Toggle switch with customizable styling and labels';
    protected ?string $icon = 'toggle-on';
    protected int $sortOrder = 60;

    protected array $defaultConfig = [
        'value' => false,
        'label' => 'Enable notifications',
        'showLabel' => true,
        'labelPosition' => 'right',
        'activeColor' => '#10B981',
        'inactiveColor' => '#D1D5DB',
        'thumbColor' => '#FFFFFF',
        'trackColor' => '#F3F4F6',
        'focusColor' => '#10B981',
        'splashRadius' => 20,
        'materialTapTargetSize' => 'padded',
        'disabled' => false,
        'autofocus' => false,
        'semanticLabel' => '',
        'thumbIcon' => null,
        'scale' => 1.0,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            BooleanField::create('value', 'Default Value', false)
                ->helpText('Initial switch state'),
            TextField::create('label', 'Label', 'Enable notifications', false)
                ->placeholder('Enter switch label')
                ->maxLength(100)
                ->helpText('Label text for the switch'),
            BooleanField::create('showLabel', 'Show Label', true)
                ->helpText('Display label next to switch'),
            SelectField::create('labelPosition', 'Label Position', [
                'left' => 'Left of switch',
                'right' => 'Right of switch',
                'top' => 'Above switch',
                'bottom' => 'Below switch',
            ], 'right')
                ->helpText('Position of the label relative to switch'),
            ColorField::create('activeColor', 'Active Color', '#10B981')
                ->helpText('Color when switch is ON'),
            ColorField::create('inactiveColor', 'Inactive Color', '#D1D5DB')
                ->helpText('Color when switch is OFF'),
            ColorField::create('thumbColor', 'Thumb Color', '#FFFFFF')
                ->helpText('Color of the switch thumb'),
            ColorField::create('trackColor', 'Track Color', '#F3F4F6')
                ->helpText('Background color of the track'),
            ColorField::create('focusColor', 'Focus Color', '#10B981')
                ->helpText('Color when switch is focused'),
            NumberField::create('splashRadius', 'Splash Radius', 20, false, 10, 50)
                ->helpText('Radius of the tap splash effect'),
            SelectField::create('materialTapTargetSize', 'Tap Target Size', [
                'padded' => 'Padded (48dp minimum)',
                'shrinkWrap' => 'Shrink Wrap (actual size)',
            ], 'padded')
                ->helpText('Size of the touch target'),
            BooleanField::create('disabled', 'Disabled', false)
                ->helpText('Disable the switch'),
            BooleanField::create('autofocus', 'Auto Focus', false)
                ->helpText('Automatically focus when rendered'),
            TextField::create('semanticLabel', 'Semantic Label', '', false)
                ->placeholder('Accessibility label')
                ->maxLength(100)
                ->helpText('Label for screen readers'),
            NumberField::create('scale', 'Scale', 1.0, false, 0.5, 2.0)
                ->step(0.1)
                ->helpText('Scale factor for the switch size'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'Switch',
            'data' => [
                'value' => $mergedConfig['value'],
                'label' => $mergedConfig['label'],
                'showLabel' => $mergedConfig['showLabel'],
                'labelPosition' => $mergedConfig['labelPosition'],
                'disabled' => $mergedConfig['disabled'],
                'autofocus' => $mergedConfig['autofocus'],
                'semanticLabel' => $mergedConfig['semanticLabel'] ?: $mergedConfig['label'],
                'thumbIcon' => $mergedConfig['thumbIcon'],
                'style' => [
                    'activeColor' => $mergedConfig['activeColor'],
                    'inactiveColor' => $mergedConfig['inactiveColor'],
                    'thumbColor' => $mergedConfig['thumbColor'],
                    'trackColor' => $mergedConfig['trackColor'],
                    'focusColor' => $mergedConfig['focusColor'],
                    'splashRadius' => (float) $mergedConfig['splashRadius'],
                    'materialTapTargetSize' => $mergedConfig['materialTapTargetSize'],
                    'scale' => (float) $mergedConfig['scale'],
                    'labelStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['disabled'] ? '#9CA3AF' : '#374151',
                    ],
                ],
                'constraints' => [
                    'minWidth' => 40.0,
                    'minHeight' => 24.0,
                ],
                'animation' => [
                    'duration' => 200,
                    'curve' => 'easeInOut',
                ],
            ],
        ];
    }
}