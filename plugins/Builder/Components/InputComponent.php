<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class InputComponent extends BaseComponent
{
    protected string $name = 'Input';
    protected string $type = 'Input';
    protected string $category = 'basic';
    protected string $description = 'Text input field with validation and customization options';
    protected ?string $icon = 'input';
    protected int $sortOrder = 4;

    protected array $defaultConfig = [
        'placeholder' => 'Enter your email',
        'label' => 'Email Address',
        'required' => false,
        'type' => 'email',
        'borderRadius' => 8,
        'maxLength' => null,
        'enabled' => true,
    ];

    public function getFieldDefinitions(): array
    {
        return [
            TextField::create('label', 'Label', 'Email Address', false, 'Field label'),
            TextField::create('placeholder', 'Placeholder', 'Enter your email', false, 'Placeholder text'),
            SelectField::create('type', 'Input Type', [
                'text' => 'Text',
                'email' => 'Email',
                'password' => 'Password',
                'number' => 'Number',
                'phone' => 'Phone',
                'url' => 'URL',
            ], 'email'),
            SelectField::create('required', 'Required', [
                'false' => 'Optional',
                'true' => 'Required',
            ], 'false'),
            NumberField::create('maxLength', 'Max Length', null, false, 1, 500, 'Maximum character limit'),
            NumberField::create('borderRadius', 'Border Radius', 8, false, 0, 50, 'Rounded corners'),
            SelectField::create('enabled', 'Enabled', [
                'true' => 'Enabled',
                'false' => 'Disabled',
            ], 'true'),
        ];
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'TextField',
            'data' => [
                'label' => $mergedConfig['label'],
                'placeholder' => $mergedConfig['placeholder'],
                'inputType' => $this->mapInputType($mergedConfig['type']),
                'required' => $mergedConfig['required'] === 'true' || $mergedConfig['required'] === true,
                'maxLength' => $mergedConfig['maxLength'] ? (int) $mergedConfig['maxLength'] : null,
                'enabled' => $mergedConfig['enabled'] === 'true' || $mergedConfig['enabled'] === true,
                'decoration' => [
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                    'borderColor' => '#E5E7EB',
                    'focusedBorderColor' => '#3B82F6',
                ],
            ],
        ];
    }

    private function mapInputType(string $type): string
    {
        return match ($type) {
            'text' => 'TextInputType.text',
            'email' => 'TextInputType.emailAddress',
            'password' => 'TextInputType.visiblePassword',
            'number' => 'TextInputType.number',
            'phone' => 'TextInputType.phone',
            'url' => 'TextInputType.url',
            default => 'TextInputType.text',
        };
    }
}