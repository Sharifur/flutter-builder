<?php

namespace Plugins\Builder\Fields;

use Plugins\Builder\Contracts\FieldInterface;

abstract class BaseField implements FieldInterface
{
    protected string $key;
    protected string $type;
    protected string $label;
    protected mixed $default;
    protected bool $required;
    protected array $attributes;
    protected array $validationRules;

    public function __construct(
        string $key,
        string $label,
        mixed $default = null,
        bool $required = false,
        array $attributes = [],
        array $validationRules = []
    ) {
        $this->key = $key;
        $this->label = $label;
        $this->default = $default;
        $this->required = $required;
        $this->attributes = $attributes;
        $this->validationRules = $validationRules;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function setAttribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge([
            'key' => $this->key,
            'type' => $this->type,
            'label' => $this->label,
            'default' => $this->default,
            'required' => $this->required,
        ], $this->attributes);
    }

    public function validate(mixed $value): bool
    {
        if ($this->required && ($value === null || $value === '')) {
            return false;
        }

        return $this->performValidation($value);
    }

    abstract protected function performValidation(mixed $value): bool;

    public static function create(string $key, string $label, mixed $default = null, bool $required = false, ...$args): static
    {
        // Allow subclasses to have different signatures
        return new static($key, $label, $default, $required, []);
    }
}