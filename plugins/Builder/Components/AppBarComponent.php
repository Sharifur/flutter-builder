<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\ArrayField;
use Plugins\Builder\Fields\IconField;

class AppBarComponent extends BaseComponent
{
    protected string $name = 'App Bar';
    protected string $type = 'AppBar';
    protected string $category = 'navigation';
    protected string $description = 'Top navigation bar with title, actions and back button';
    protected ?string $icon = 'navigation';
    protected int $sortOrder = 10;

    protected array $defaultConfig = [
        'title' => 'App Title',
        'centerTitle' => true,
        'showBackButton' => false,
        'backgroundColor' => '#6366F1',
        'foregroundColor' => '#FFFFFF',
        'elevation' => 4,
        'height' => 56,
        'backButtonIcon' => 'arrow_back',
        'actions' => [
            ['icon' => 'search', 'label' => 'Search'],
            ['icon' => 'more_vert', 'label' => 'More'],
        ],
        'showActions' => true,
        'titleStyle' => [
            'fontSize' => 20,
            'fontWeight' => 'w600',
        ],
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('title', 'Title', 'App Title', true)
                ->placeholder('Enter app bar title')
                ->maxLength(50)
                ->helpText('Main title displayed in the app bar'),
            BooleanField::create('centerTitle', 'Center Title', true)
                ->helpText('Center the title horizontally'),
            BooleanField::create('showBackButton', 'Show Back Button', false)
                ->helpText('Display back/up navigation button'),
            IconField::create('backButtonIcon', 'Back Button Icon', 'arrow_back')
                ->helpText('Icon for the back button'),
            ColorField::create('backgroundColor', 'Background Color', '#6366F1')
                ->helpText('App bar background color'),
            ColorField::create('foregroundColor', 'Text Color', '#FFFFFF')
                ->helpText('Color for text and icons'),
            NumberField::create('elevation', 'Elevation', 4, false, 0, 20)
                ->helpText('Shadow depth below the app bar'),
            NumberField::create('height', 'Height', 56, false, 40, 100)
                ->helpText('App bar height in pixels'),
            BooleanField::create('showActions', 'Show Actions', true)
                ->helpText('Display action buttons'),
            NumberField::create('titleFontSize', 'Title Font Size', 20, false, 12, 32)
                ->helpText('Font size for the title'),
            SelectField::create('titleFontWeight', 'Title Font Weight', [
                'w400' => 'Normal',
                'w500' => 'Medium',
                'w600' => 'Semi Bold',
                'w700' => 'Bold',
            ], 'w600')
                ->helpText('Font weight for the title'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'AppBar',
            'data' => [
                'title' => $mergedConfig['title'],
                'centerTitle' => $mergedConfig['centerTitle'],
                'showBackButton' => $mergedConfig['showBackButton'],
                'backButtonIcon' => $mergedConfig['backButtonIcon'],
                'showActions' => $mergedConfig['showActions'],
                'actions' => $mergedConfig['actions'],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'foregroundColor' => $mergedConfig['foregroundColor'],
                    'elevation' => (float) $mergedConfig['elevation'],
                    'height' => (float) $mergedConfig['height'],
                    'titleStyle' => [
                        'fontSize' => (float) ($mergedConfig['titleFontSize'] ?? $mergedConfig['titleStyle']['fontSize']),
                        'fontWeight' => $mergedConfig['titleFontWeight'] ?? $mergedConfig['titleStyle']['fontWeight'],
                        'color' => $mergedConfig['foregroundColor'],
                    ],
                    'iconTheme' => [
                        'color' => $mergedConfig['foregroundColor'],
                        'size' => 24.0,
                    ],
                ],
                'systemOverlayStyle' => [
                    'statusBarColor' => $mergedConfig['backgroundColor'],
                    'statusBarIconBrightness' => $this->getBrightness($mergedConfig['backgroundColor']),
                ],
            ],
        ];
    }

    private function getBrightness(string $hexColor): string
    {
        // Convert hex to RGB and calculate brightness
        $hex = str_replace('#', '', $hexColor);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;

        return $brightness > 127 ? 'dark' : 'light';
    }
}