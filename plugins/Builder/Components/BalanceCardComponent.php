<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\BooleanField;
use Plugins\Builder\Fields\NumberField;

class BalanceCardComponent extends BaseComponent
{
    protected string $name = 'Balance Card';
    protected string $type = 'BalanceCard';
    protected string $category = 'financial';
    protected string $description = 'Financial balance card with gradient and currency display';
    protected ?string $icon = 'credit-card';
    protected int $sortOrder = 20;

    protected array $defaultConfig = [
        'title' => 'Total Balance',
        'amount' => '$1,234.56',
        'currency' => 'USD',
        'backgroundColor' => '#6366F1',
        'textColor' => '#FFFFFF',
        'gradient' => true,
        'borderRadius' => 16,
        'showCurrency' => true,
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('title', 'Title', 'Total Balance', true)
                ->placeholder('Enter card title')
                ->maxLength(30),
            TextField::create('amount', 'Amount', '$1,234.56', true)
                ->placeholder('Enter amount')
                ->helpText('Include currency symbol if needed'),
            TextField::create('currency', 'Currency', 'USD', false)
                ->placeholder('USD')
                ->maxLength(10)
                ->helpText('Currency code or symbol'),
            ColorField::create('backgroundColor', 'Background Color', '#6366F1')
                ->helpText('Primary card color'),
            ColorField::create('textColor', 'Text Color', '#FFFFFF')
                ->helpText('Text color'),
            BooleanField::create('gradient', 'Gradient Effect', true)
                ->helpText('Apply gradient effect to background'),
            BooleanField::create('showCurrency', 'Show Currency', true)
                ->helpText('Display currency separately'),
            NumberField::create('borderRadius', 'Border Radius', 16, false, 0, 30)
                ->helpText('Card corner rounding'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'BalanceCard',
            'data' => [
                'title' => $mergedConfig['title'],
                'amount' => $mergedConfig['amount'],
                'currency' => $mergedConfig['currency'],
                'showCurrency' => $mergedConfig['showCurrency'],
                'style' => [
                    'backgroundColor' => $mergedConfig['backgroundColor'],
                    'textColor' => $mergedConfig['textColor'],
                    'gradient' => $mergedConfig['gradient'],
                    'borderRadius' => (float) $mergedConfig['borderRadius'],
                    'titleStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w400',
                        'color' => $mergedConfig['textColor'],
                        'opacity' => 0.8,
                    ],
                    'amountStyle' => [
                        'fontSize' => 28.0,
                        'fontWeight' => 'w700',
                        'color' => $mergedConfig['textColor'],
                    ],
                    'currencyStyle' => [
                        'fontSize' => 12.0,
                        'fontWeight' => 'w500',
                        'color' => $mergedConfig['textColor'],
                        'opacity' => 0.9,
                    ],
                ],
                'padding' => 20.0,
                'margin' => 8.0,
            ],
        ];
    }
}