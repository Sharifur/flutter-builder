<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\TextareaField;

class BottomSheetComponent extends BaseComponent
{
    protected string $name = 'Bottom Sheet';
    protected string $type = 'BottomSheet';
    protected string $category = 'layout';
    protected string $description = 'Modal bottom sheet with draggable handle and customizable content';
    protected ?string $icon = 'view-agenda';
    protected int $sortOrder = 52;

    protected array $defaultConfig = [
        'title' => 'Bottom Sheet',
        'content' => 'This is the content of the bottom sheet. You can add any widgets here.',
        'showHandle' => true,
        'isDismissible' => true,
        'enableDrag' => true,
        'isScrollControlled' => false,
        'initialHeight' => 0.4,
        'maxHeight' => 0.9,
        'minHeight' => 0.2,
        'backgroundColor' => '#FFFFFF',
        'handleColor' => '#E5E7EB',
        'borderRadius' => 20,
        'showCloseButton' => true,
        'closeButtonIcon' => 'close',
        'padding' => [
            'top' => 20,
            'bottom' => 20,
            'left' => 16,
            'right' => 16,
        ],
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('title', 'Title', 'Bottom Sheet', false)
                ->placeholder('Enter sheet title')
                ->maxLength(100)
                ->helpText('Optional title for the bottom sheet'),
            TextareaField::create('content', 'Content', 'This is the content of the bottom sheet. You can add any widgets here.', true)
                ->placeholder('Enter content text')
                ->maxLength(500)
                ->rows(4)
                ->helpText('Main content text'),
            BooleanField::create('showHandle', 'Show Handle', true)
                ->helpText('Display draggable handle at the top'),
            BooleanField::create('isDismissible', 'Dismissible', true)
                ->helpText('Allow dismissing by tapping outside'),
            BooleanField::create('enableDrag', 'Enable Drag', true)
                ->helpText('Allow dragging to resize/dismiss'),
            BooleanField::create('isScrollControlled', 'Scroll Controlled', false)
                ->helpText('Enable scrolling within the sheet'),
            NumberField::create('initialHeight', 'Initial Height', 0.4, false, 0.1, 1.0)
                ->step(0.1)
                ->helpText('Initial height as screen percentage'),
            NumberField::create('maxHeight', 'Max Height', 0.9, false, 0.1, 1.0)
                ->step(0.1)
                ->helpText('Maximum height as screen percentage'),
            NumberField::create('minHeight', 'Min Height', 0.2, false, 0.1, 1.0)
                ->step(0.1)
                ->helpText('Minimum height as screen percentage'),
            ColorField::create('backgroundColor', 'Background Color', '#FFFFFF')
                ->helpText('Sheet background color'),
            ColorField::create('handleColor', 'Handle Color', '#E5E7EB')
                ->helpText('Color of the drag handle'),
            NumberField::create('borderRadius', 'Border Radius', 20, false, 0, 50)
                ->helpText('Corner rounding for top edges'),
            BooleanField::create('showCloseButton', 'Show Close Button', true)
                ->helpText('Display close button in header'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'BottomSheet',
            'data' => [
                'title' => $mergedConfig['title'],
                'content' => $mergedConfig['content'],
                'showHandle' => $mergedConfig['showHandle'],
                'isDismissible' => $mergedConfig['isDismissible'],
                'enableDrag' => $mergedConfig['enableDrag'],
                'isScrollControlled' => $mergedConfig['isScrollControlled'],
                'showCloseButton' => $mergedConfig['showCloseButton'],
                'closeButtonIcon' => $mergedConfig['closeButtonIcon'],
                'constraints' => [
                    'initialHeight' => (float) $mergedConfig['initialHeight'],
                    'maxHeight' => (float) $mergedConfig['maxHeight'],
                    'minHeight' => (float) $mergedConfig['minHeight'],
                ],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'handleColor' => $mergedConfig['handleColor'],
                    'borderRadius' => [
                        'topLeft' => (float) $mergedConfig['borderRadius'],
                        'topRight' => (float) $mergedConfig['borderRadius'],
                        'bottomLeft' => 0.0,
                        'bottomRight' => 0.0,
                    ],
                    'padding' => [
                        'top' => (float) $mergedConfig['padding']['top'],
                        'bottom' => (float) $mergedConfig['padding']['bottom'],
                        'left' => (float) $mergedConfig['padding']['left'],
                        'right' => (float) $mergedConfig['padding']['right'],
                    ],
                    'titleStyle' => [
                        'fontSize' => 18.0,
                        'fontWeight' => 'w600',
                        'color' => '#1F2937',
                    ],
                    'contentStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w400',
                        'color' => '#6B7280',
                        'lineHeight' => 1.5,
                    ],
                    'handleStyle' => [
                        'width' => 40.0,
                        'height' => 4.0,
                        'borderRadius' => 2.0,
                    ],
                ],
                'elevation' => 8.0,
                'barrier' => [
                    'color' => '#000000',
                    'opacity' => 0.3,
                ],
            ],
        ];
    }
}