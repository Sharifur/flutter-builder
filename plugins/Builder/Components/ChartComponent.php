<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;
use Plugins\Builder\Fields\ArrayField;

class ChartComponent extends BaseComponent
{
    protected string $name = 'Chart';
    protected string $type = 'Chart';
    protected string $category = 'data';
    protected string $description = 'Data visualization charts (bar, line, pie) with customizable styling';
    protected ?string $icon = 'bar-chart';
    protected int $sortOrder = 80;

    protected array $defaultConfig = [
        'chartType' => 'bar',
        'title' => 'Chart Title',
        'showTitle' => true,
        'data' => [
            ['label' => 'Jan', 'value' => 30],
            ['label' => 'Feb', 'value' => 45],
            ['label' => 'Mar', 'value' => 28],
            ['label' => 'Apr', 'value' => 52],
            ['label' => 'May', 'value' => 38],
            ['label' => 'Jun', 'value' => 61],
        ],
        'colors' => ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4'],
        'backgroundColor' => '#FFFFFF',
        'gridColor' => '#E5E7EB',
        'textColor' => '#374151',
        'showGrid' => true,
        'showLegend' => true,
        'showLabels' => true,
        'showValues' => false,
        'animated' => true,
        'animationDuration' => 1000,
        'borderRadius' => 8,
        'strokeWidth' => 2,
        'height' => 300,
        'maxValue' => null,
        'minValue' => null,
        'legendPosition' => 'bottom',
        'labelRotation' => 0,
        'showTooltip' => true,
        'gradient' => false,
        'shadowColor' => 'rgba(0, 0, 0, 0.1)',
        'showShadow' => true,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            SelectField::create('chartType', 'Chart Type', [
                'bar' => 'Bar Chart',
                'line' => 'Line Chart',
                'pie' => 'Pie Chart',
                'doughnut' => 'Doughnut Chart',
                'area' => 'Area Chart',
                'scatter' => 'Scatter Plot',
            ], 'bar')
                ->helpText('Type of chart to display'),
            TextField::create('title', 'Chart Title', 'Chart Title', false)
                ->placeholder('Enter chart title')
                ->maxLength(100)
                ->helpText('Title displayed above the chart'),
            BooleanField::create('showTitle', 'Show Title', true)
                ->helpText('Display the chart title'),
            ArrayField::create('data', 'Chart Data', [
                ['label' => 'Jan', 'value' => 30],
                ['label' => 'Feb', 'value' => 45],
                ['label' => 'Mar', 'value' => 28],
            ], true)
                ->itemType('object')
                ->minItems(1)
                ->maxItems(20)
                ->helpText('Data points for the chart'),
            ArrayField::create('colors', 'Colors', ['#3B82F6', '#10B981', '#F59E0B'], false)
                ->itemType('color')
                ->minItems(1)
                ->maxItems(10)
                ->helpText('Colors for chart elements'),
            ColorField::create('backgroundColor', 'Background Color', '#FFFFFF')
                ->helpText('Chart background color'),
            ColorField::create('gridColor', 'Grid Color', '#E5E7EB')
                ->helpText('Color of grid lines'),
            ColorField::create('textColor', 'Text Color', '#374151')
                ->helpText('Color for labels and text'),
            BooleanField::create('showGrid', 'Show Grid', true)
                ->helpText('Display grid lines'),
            BooleanField::create('showLegend', 'Show Legend', true)
                ->helpText('Display chart legend'),
            BooleanField::create('showLabels', 'Show Labels', true)
                ->helpText('Display data labels'),
            BooleanField::create('showValues', 'Show Values', false)
                ->helpText('Display data values on chart'),
            BooleanField::create('animated', 'Animated', true)
                ->helpText('Enable chart animations'),
            NumberField::create('animationDuration', 'Animation Duration', 1000, false, 100, 5000)
                ->helpText('Animation duration in milliseconds'),
            NumberField::create('borderRadius', 'Border Radius', 8, false, 0, 30)
                ->helpText('Corner rounding for chart container'),
            NumberField::create('strokeWidth', 'Line Width', 2, false, 1, 10)
                ->helpText('Width of lines and borders'),
            NumberField::create('height', 'Height', 300, false, 100, 800)
                ->helpText('Chart height in pixels'),
            NumberField::create('maxValue', 'Max Value', null, false, 0, 10000)
                ->helpText('Maximum value for Y-axis (auto if empty)'),
            NumberField::create('minValue', 'Min Value', null, false, -1000, 1000)
                ->helpText('Minimum value for Y-axis (auto if empty)'),
            SelectField::create('legendPosition', 'Legend Position', [
                'top' => 'Top',
                'bottom' => 'Bottom',
                'left' => 'Left',
                'right' => 'Right',
                'none' => 'Hidden',
            ], 'bottom')
                ->helpText('Position of the legend'),
            NumberField::create('labelRotation', 'Label Rotation', 0, false, -90, 90)
                ->helpText('Rotation angle for X-axis labels'),
            BooleanField::create('showTooltip', 'Show Tooltip', true)
                ->helpText('Display tooltip on hover'),
            BooleanField::create('gradient', 'Gradient Fill', false)
                ->helpText('Use gradient fills for chart elements'),
            BooleanField::create('showShadow', 'Show Shadow', true)
                ->helpText('Display shadow effect'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'Chart',
            'data' => [
                'chartType' => $mergedConfig['chartType'],
                'title' => $mergedConfig['title'],
                'showTitle' => $mergedConfig['showTitle'],
                'data' => $mergedConfig['data'],
                'colors' => $mergedConfig['colors'],
                'height' => (float) $mergedConfig['height'],
                'maxValue' => $mergedConfig['maxValue'] ? (float) $mergedConfig['maxValue'] : null,
                'minValue' => $mergedConfig['minValue'] ? (float) $mergedConfig['minValue'] : null,
                'display' => [
                    'showGrid' => $mergedConfig['showGrid'],
                    'showLegend' => $mergedConfig['showLegend'],
                    'showLabels' => $mergedConfig['showLabels'],
                    'showValues' => $mergedConfig['showValues'],
                    'showTooltip' => $mergedConfig['showTooltip'],
                    'showShadow' => $mergedConfig['showShadow'],
                    'legendPosition' => $mergedConfig['legendPosition'],
                ],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'gridColor' => $mergedConfig['gridColor'],
                    'textColor' => $mergedConfig['textColor'],
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                    'strokeWidth' => (float) $mergedConfig['strokeWidth'],
                    'shadowColor' => $mergedConfig['shadowColor'],
                    'gradient' => $mergedConfig['gradient'],
                    'titleStyle' => [
                        'fontSize' => 18.0,
                        'fontWeight' => 'w600',
                        'color' => $mergedConfig['textColor'],
                        'marginBottom' => 16.0,
                    ],
                    'labelStyle' => [
                        'fontSize' => 12.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['textColor'],
                        'rotation' => (float) $mergedConfig['labelRotation'],
                    ],
                    'legendStyle' => [
                        'fontSize' => 12.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['textColor'],
                    ],
                    'tooltipStyle' => [
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'textColor' => '#FFFFFF',
                        'borderRadius' => 4.0,
                        'padding' => 8.0,
                    ],
                ],
                'animation' => [
                    'enabled' => $mergedConfig['animated'],
                    'duration' => (int) $mergedConfig['animationDuration'],
                    'curve' => 'easeInOut',
                ],
                'interaction' => [
                    'enableTouch' => true,
                    'enablePan' => $mergedConfig['chartType'] === 'line',
                    'enableZoom' => $mergedConfig['chartType'] === 'line',
                ],
            ],
        ];
    }
}