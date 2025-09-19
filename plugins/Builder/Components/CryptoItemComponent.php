<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Fields\TextField;
use Plugins\Builder\Fields\ColorField;
use Plugins\Builder\Fields\ImageField;
use Plugins\Builder\Fields\NumberField;
use Plugins\Builder\Fields\SelectField;

class CryptoItemComponent extends BaseComponent
{
    protected string $name = 'Crypto Item';
    protected string $type = 'CryptoItem';
    protected string $category = 'financial';
    protected string $description = 'Cryptocurrency item with price and change indicator';
    protected ?string $icon = 'trending-up';
    protected int $sortOrder = 21;

    protected array $defaultConfig = [
        'name' => 'Bitcoin',
        'symbol' => 'BTC',
        'amount' => '0.1234',
        'value' => '$5,678.90',
        'change' => '+2.15%',
        'changeColor' => '#10B981',
        'icon' => 'https://via.placeholder.com/40x40',
        'trend' => 'up',
    ];

    public function getFieldDefinitions(): array
    {
        $this->fieldDefinitions = [
            TextField::create('name', 'Cryptocurrency Name', 'Bitcoin', true)
                ->placeholder('Enter crypto name')
                ->maxLength(30),
            TextField::create('symbol', 'Symbol', 'BTC', true)
                ->placeholder('BTC')
                ->maxLength(10)
                ->helpText('Currency symbol (e.g., BTC, ETH)'),
            TextField::create('amount', 'Amount Owned', '0.1234', false)
                ->placeholder('0.0000')
                ->helpText('Amount you own'),
            TextField::create('value', 'Current Value', '$5,678.90', true)
                ->placeholder('$0.00')
                ->helpText('Current USD value'),
            TextField::create('change', 'Price Change', '+2.15%', false)
                ->placeholder('+0.00%')
                ->helpText('Price change percentage'),
            SelectField::create('trend', 'Trend Direction', [
                'up' => 'Upward',
                'down' => 'Downward',
                'neutral' => 'Neutral',
            ], 'up'),
            ColorField::create('changeColor', 'Change Color', '#10B981')
                ->helpText('Color for price change indicator'),
            ImageField::create('icon', 'Crypto Icon', 'https://via.placeholder.com/40x40')
                ->helpText('Cryptocurrency icon/logo'),
        ];

        return parent::getFieldDefinitions();
    }

    public function render(array $config): array
    {
        $mergedConfig = array_merge($this->defaultConfig, $config);

        return [
            'type' => 'CryptoItem',
            'data' => [
                'name' => $mergedConfig['name'],
                'symbol' => $mergedConfig['symbol'],
                'amount' => $mergedConfig['amount'],
                'value' => $mergedConfig['value'],
                'change' => $mergedConfig['change'],
                'icon' => $mergedConfig['icon'],
                'trend' => $mergedConfig['trend'],
                'style' => [
                    'nameStyle' => [
                        'fontSize' => 16.0,
                        'fontWeight' => 'w600',
                        'color' => '#1F2937',
                    ],
                    'symbolStyle' => [
                        'fontSize' => 12.0,
                        'fontWeight' => 'w400',
                        'color' => '#6B7280',
                    ],
                    'valueStyle' => [
                        'fontSize' => 16.0,
                        'fontWeight' => 'w600',
                        'color' => '#1F2937',
                    ],
                    'changeStyle' => [
                        'fontSize' => 14.0,
                        'fontWeight' => 'w500',
                        'color' => $this->getTrendColor($mergedConfig['trend'], $mergedConfig['changeColor']),
                    ],
                ],
                'iconSize' => 40.0,
                'padding' => 16.0,
                'borderRadius' => 12.0,
                'backgroundColor' => '#FFFFFF',
            ],
        ];
    }

    private function getTrendColor(string $trend, string $defaultColor): string
    {
        return match ($trend) {
            'up' => '#10B981',    // Green
            'down' => '#EF4444',  // Red
            'neutral' => '#6B7280', // Gray
            default => $defaultColor,
        };
    }
}