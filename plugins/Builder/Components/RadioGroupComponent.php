<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\ArrayField;

class RadioGroupComponent extends BaseComponent
{
    protected string $name = 'Radio Group';
    protected string $type = 'RadioGroup';
    protected string $category = 'forms';
    protected string $description = 'Radio button group with multiple options and single selection';
    protected ?string $icon = 'radio-button-checked';
    protected int $sortOrder = 62;

    protected array $defaultConfig = [
        'value' => 'option1',
        'options' => [
            'option1' => 'Option 1',
            'option2' => 'Option 2',
            'option3' => 'Option 3',
        ],
        'title' => 'Select an option',
        'showTitle' => true,
        'direction' => 'vertical',
        'activeColor' => '#3B82F6',
        'focusColor' => '#3B82F6',
        'hoverColor' => '#DBEAFE',
        'overlayColor' => '#3B82F6',
        'splashRadius' => 20,
        'materialTapTargetSize' => 'padded',
        'visualDensity' => 'standard',
        'toggleable' => false,
        'disabled' => false,
        'autofocus' => false,
        'spacing' => 8,
        'fillColor' => '#3B82F6',
        'groupValue' => null,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('title', 'Group Title', 'Select an option', false)
                ->placeholder('Enter group title')
                ->maxLength(100)
                ->helpText('Title for the radio group'),
            BooleanField::create('showTitle', 'Show Title', true)
                ->helpText('Display the group title'),
            TextField::create('value', 'Default Value', 'option1', false)
                ->placeholder('Enter default option key')
                ->maxLength(50)
                ->helpText('Key of the default selected option'),
            ArrayField::create('options', 'Options', ['option1' => 'Option 1', 'option2' => 'Option 2', 'option3' => 'Option 3'], true)
                ->itemType('text')
                ->minItems(2)
                ->maxItems(10)
                ->helpText('Radio button options (key => label)'),
            SelectField::create('direction', 'Direction', [
                'vertical' => 'Vertical',
                'horizontal' => 'Horizontal',
            ], 'vertical')
                ->helpText('Layout direction of radio buttons'),
            ColorField::create('activeColor', 'Active Color', '#3B82F6')
                ->helpText('Color when radio is selected'),
            ColorField::create('focusColor', 'Focus Color', '#3B82F6')
                ->helpText('Color when focused'),
            ColorField::create('hoverColor', 'Hover Color', '#DBEAFE')
                ->helpText('Color when hovered'),
            ColorField::create('fillColor', 'Fill Color', '#3B82F6')
                ->helpText('Fill color for selected radio'),
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
                ->helpText('Visual density of radio buttons'),
            NumberField::create('spacing', 'Spacing', 8, false, 0, 30)
                ->helpText('Spacing between radio options'),
            BooleanField::create('toggleable', 'Toggleable', false)
                ->helpText('Allow deselection by tapping selected option'),
            BooleanField::create('disabled', 'Disabled', false)
                ->helpText('Disable the entire radio group'),
            BooleanField::create('autofocus', 'Auto Focus', false)
                ->helpText('Automatically focus first option'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'RadioGroup',
            'data' => [
                'value' => $mergedConfig['value'],
                'options' => $mergedConfig['options'],
                'title' => $mergedConfig['title'],
                'showTitle' => $mergedConfig['showTitle'],
                'direction' => $mergedConfig['direction'],
                'toggleable' => $mergedConfig['toggleable'],
                'disabled' => $mergedConfig['disabled'],
                'autofocus' => $mergedConfig['autofocus'],
                'groupValue' => $mergedConfig['value'],
                'style' => [
                    'activeColor' => $mergedConfig['activeColor'],
                    'focusColor' => $mergedConfig['focusColor'],
                    'hoverColor' => $mergedConfig['hoverColor'],
                    'overlayColor' => $mergedConfig['overlayColor'],
                    'fillColor' => $mergedConfig['fillColor'],
                    'splashRadius' => (float) $mergedConfig['splashRadius'],
                    'materialTapTargetSize' => $mergedConfig['materialTapTargetSize'],
                    'visualDensity' => $mergedConfig['visualDensity'],
                    'spacing' => (float) $mergedConfig['spacing'],
                    'titleStyle' => [
                        'fontSize' => 16.0,
                        'fontWeight' => 'w600',
                        'color' => '#374151',
                        'marginBottom' => 12.0,
                    ],
                    'labelStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['disabled'] ? '#9CA3AF' : '#374151',
                    ],
                ],
                'layout' => [
                    'mainAxisSize' => 'min',
                    'crossAxisAlignment' => 'start',
                    'children' => $this->buildRadioOptions($mergedConfig),
                ],
            ],
        ];
    }

    private function buildRadioOptions(array $config): array
    {
        $options = [];
        foreach ($config['options'] as $key => $label) {
            $options[] = [
                'key' => $key,
                'label' => $label,
                'value' => $key,
                'groupValue' => $config['value'],
                'selected' => $key === $config['value'],
            ];
        }
        return $options;
    }
}