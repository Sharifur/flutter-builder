<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Widget extends Model
{
    protected $fillable = [
        'app_page_id',
        'type',
        'config',
        'order',
    ];

    protected $casts = [
        'config' => 'array',
    ];

    public function appPage(): BelongsTo
    {
        return $this->belongsTo(AppPage::class);
    }

    public static function getDefaultConfig(string $type): array
    {
        return match ($type) {
            'Text' => [
                'value' => 'Welcome to your app',
                'color' => '#1F2937',
                'fontSize' => 18,
                'fontWeight' => '600',
                'textAlign' => 'left',
            ],
            'Button' => [
                'label' => 'Get Started',
                'color' => '#3B82F6',
                'textColor' => '#FFFFFF',
                'action' => null,
                'borderRadius' => 8,
                'size' => 'medium',
            ],
            'Image' => [
                'url' => 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=300&h=200&fit=crop',
                'width' => 300,
                'height' => 200,
                'alt' => 'Beautiful image',
                'borderRadius' => 8,
            ],
            'Input' => [
                'placeholder' => 'Enter your email',
                'label' => 'Email Address',
                'required' => false,
                'type' => 'email',
                'borderRadius' => 8,
            ],
            'Container' => [
                'direction' => 'column', // column or row
                'spacing' => 12,
                'padding' => 20,
                'backgroundColor' => '#F9FAFB',
                'borderRadius' => 12,
                'children' => [],
            ],
            'Card' => [
                'title' => 'Card Title',
                'subtitle' => 'Card subtitle',
                'backgroundColor' => '#FFFFFF',
                'borderRadius' => 12,
                'shadow' => true,
                'padding' => 16,
            ],
            'ProfileHeader' => [
                'name' => 'John Doe',
                'avatar' => 'https://via.placeholder.com/60x60',
                'backgroundColor' => '#6366F1',
                'textColor' => '#FFFFFF',
            ],
            'BalanceCard' => [
                'title' => 'Total Balance',
                'amount' => '$1,234.56',
                'currency' => 'USD',
                'backgroundColor' => '#6366F1',
                'textColor' => '#FFFFFF',
                'gradient' => true,
            ],
            'ActionButton' => [
                'icon' => 'plus',
                'label' => 'Add',
                'backgroundColor' => '#FFFFFF',
                'iconColor' => '#6366F1',
                'textColor' => '#374151',
            ],
            'CryptoItem' => [
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'amount' => '0.1234',
                'value' => '$5,678.90',
                'change' => '+2.15%',
                'changeColor' => '#10B981',
                'icon' => 'https://via.placeholder.com/40x40',
            ],
            'TransactionItem' => [
                'title' => 'Transaction',
                'subtitle' => '2023-07-25',
                'amount' => '+$100.00',
                'amountColor' => '#10B981',
                'icon' => 'exchange',
            ],
            'TabBar' => [
                'tabs' => ['Dashboard', 'Cards', 'Accounts', 'Settings'],
                'activeTab' => 0,
                'backgroundColor' => '#FFFFFF',
                'activeColor' => '#6366F1',
                'inactiveColor' => '#9CA3AF',
            ],
            'StatCard' => [
                'title' => 'Total Customer',
                'value' => '32,502',
                'change' => '2.1%',
                'changeType' => 'decrease',
                'icon' => 'users',
                'iconColor' => '#6366F1',
            ],
            'ChartCard' => [
                'title' => 'Sales Overview',
                'chartType' => 'bar',
                'period' => 'Monthly',
                'data' => [40, 30, 50, 45],
                'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            ],
            'HeaderCard' => [
                'title' => 'Overview',
                'avatar' => 'https://via.placeholder.com/40x40',
                'showNotification' => true,
                'notificationCount' => 2,
            ],
            'ProductCard' => [
                'name' => 'Nike Adapt Quick',
                'model' => 'HQ240V',
                'price' => '$110.50',
                'image' => 'https://via.placeholder.com/150x150',
                'favorite' => false,
            ],
            'ExpenseItem' => [
                'category' => 'Food And Beverage',
                'amount' => '$214',
                'icon' => 'utensils',
                'iconColor' => '#F59E0B',
                'percentage' => '25%',
            ],
            'BalanceChart' => [
                'title' => 'Current Balance',
                'amount' => '$42,450.75',
                'currency' => 'USD',
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                'activeMonth' => 'Mar',
                'gradient' => true,
            ],
            'SearchHeader' => [
                'title' => 'iookla',
                'placeholder' => 'Search',
                'showNotification' => true,
            ],
            'NavigationTabs' => [
                'tabs' => ['Feed', 'Group', 'Interest', 'Marketplace'],
                'activeTab' => 3,
                'activeColor' => '#F97316',
            ],
            'StockChart' => [
                'title' => 'Stock Report',
                'period' => 'Monthly',
                'chartType' => 'line',
                'data' => [15, 20, 18, 25, 20, 30, 35],
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            ],
            'OrderItem' => [
                'rank' => '#1',
                'name' => 'Baked Bread',
                'orders' => '50 Order',
                'image' => 'https://via.placeholder.com/60x60',
            ],
            default => [],
        };
    }
}
