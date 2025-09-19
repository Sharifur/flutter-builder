<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Plugins\Builder\Contracts\ComponentInterface;

class UiComponent extends Model
{
    protected $fillable = [
        'name',
        'component_type',
        'category',
        'description',
        'default_config',
        'field_definitions',
        'icon',
        'preview_image',
        'is_active',
        'sort_order',
        'php_class',
        'dependencies',
    ];

    protected $casts = [
        'default_config' => 'array',
        'field_definitions' => 'array',
        'dependencies' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class, 'type', 'component_type');
    }

    public function getComponentInstance(): ?ComponentInterface
    {
        if (!class_exists($this->php_class)) {
            return null;
        }

        $instance = new $this->php_class();

        if (!$instance instanceof ComponentInterface) {
            return null;
        }

        return $instance;
    }

    public function render(array $config = []): array
    {
        $component = $this->getComponentInstance();

        if (!$component) {
            return [];
        }

        $finalConfig = array_merge($this->default_config ?? [], $config);

        if (!$component->validate($finalConfig)) {
            return [];
        }

        return $component->render($finalConfig);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}