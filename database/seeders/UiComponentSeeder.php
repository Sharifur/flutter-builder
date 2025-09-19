<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UiComponent;
use Plugins\Builder\Components\TextComponent;
use Plugins\Builder\Components\ButtonComponent;

class UiComponentSeeder extends Seeder
{
    public function run(): void
    {
        $components = [
            // Basic Components
            \Plugins\Builder\Components\TextComponent::class,
            \Plugins\Builder\Components\ButtonComponent::class,
            \Plugins\Builder\Components\ImageComponent::class,
            \Plugins\Builder\Components\InputComponent::class,
            \Plugins\Builder\Components\ContainerComponent::class,
            \Plugins\Builder\Components\CardComponent::class,

            // Layout Components
            \Plugins\Builder\Components\ListViewComponent::class,
            \Plugins\Builder\Components\GridViewComponent::class,
            \Plugins\Builder\Components\BottomSheetComponent::class,

            // Navigation Components
            \Plugins\Builder\Components\AppBarComponent::class,
            \Plugins\Builder\Components\TabBarComponent::class,

            // Form Components
            \Plugins\Builder\Components\SwitchComponent::class,
            \Plugins\Builder\Components\CheckboxComponent::class,
            \Plugins\Builder\Components\RadioGroupComponent::class,

            // Media Components
            \Plugins\Builder\Components\VideoPlayerComponent::class,
            \Plugins\Builder\Components\AudioPlayerComponent::class,

            // Data Components
            \Plugins\Builder\Components\ChartComponent::class,

            // Authentication Components
            \Plugins\Builder\Components\LoginFormComponent::class,
            \Plugins\Builder\Components\RegisterFormComponent::class,

            // Social Components
            \Plugins\Builder\Components\ProfileHeaderComponent::class,

            // Financial Components
            \Plugins\Builder\Components\BalanceCardComponent::class,
            \Plugins\Builder\Components\CryptoItemComponent::class,

            // Dashboard Components
            \Plugins\Builder\Components\StatCardComponent::class,
        ];

        foreach ($components as $componentClass) {
            $component = new $componentClass();

            UiComponent::updateOrCreate(
                [
                    'component_type' => $component->getType(),
                ],
                [
                    'name' => $component->getName(),
                    'category' => $component->getCategory(),
                    'description' => $component->getDescription(),
                    'default_config' => $component->getDefaultConfig(),
                    'field_definitions' => $component->getFieldDefinitions(),
                    'icon' => $component->getIcon(),
                    'preview_image' => $component->getPreviewImage(),
                    'is_active' => $component->isActive(),
                    'sort_order' => $component->getSortOrder(),
                    'php_class' => $componentClass,
                    'dependencies' => $component->getDependencies(),
                ]
            );
        }
    }
}