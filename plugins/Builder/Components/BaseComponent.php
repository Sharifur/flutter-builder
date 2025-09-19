<?php

namespace Plugins\Builder\Components;

use Plugins\Builder\Contracts\ComponentInterface;

abstract class BaseComponent implements ComponentInterface
{
    protected string $name;
    protected string $type;
    protected string $category = 'general';
    protected string $description = '';
    protected ?string $icon = null;
    protected ?string $previewImage = null;
    protected array $defaultConfig = [];
    protected array $fieldDefinitions = [];
    protected array $dependencies = [];
    protected int $sortOrder = 0;
    protected bool $isActive = true;

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getPreviewImage(): ?string
    {
        return $this->previewImage;
    }

    public function getDefaultConfig(): array
    {
        return $this->defaultConfig;
    }

    public function getFieldDefinitions(): array
    {
        // Convert field objects to arrays for API responses
        return array_map(function ($field) {
            if (is_object($field) && method_exists($field, 'toArray')) {
                return $field->toArray();
            }
            return $field;
        }, $this->fieldDefinitions);
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function validate(array $config): bool
    {
        $schema = $this->getConfigSchema();

        foreach ($schema as $field => $rules) {
            if ($rules['required'] ?? false) {
                if (!isset($config[$field]) || empty($config[$field])) {
                    return false;
                }
            }

            if (isset($config[$field]) && isset($rules['type'])) {
                $value = $config[$field];
                $type = $rules['type'];

                switch ($type) {
                    case 'string':
                        if (!is_string($value)) return false;
                        break;
                    case 'integer':
                        if (!is_int($value)) return false;
                        break;
                    case 'boolean':
                        if (!is_bool($value)) return false;
                        break;
                    case 'array':
                        if (!is_array($value)) return false;
                        break;
                    case 'color':
                        if (!preg_match('/^#[a-fA-F0-9]{6}$/', $value)) return false;
                        break;
                }
            }
        }

        return true;
    }

    public function getConfigSchema(): array
    {
        $schema = [];

        foreach ($this->fieldDefinitions as $field) {
            $schema[$field['key']] = [
                'type' => $field['type'],
                'required' => $field['required'] ?? false,
                'default' => $field['default'] ?? null,
            ];
        }

        return $schema;
    }

    abstract public function render(array $config): array;
}