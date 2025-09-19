<?php

namespace Plugins\Builder\Contracts;

interface FieldInterface
{
    public function getKey(): string;
    public function getType(): string;
    public function getLabel(): string;
    public function getDefault(): mixed;
    public function isRequired(): bool;
    public function getValidationRules(): array;
    public function getAttributes(): array;
    public function toArray(): array;
    public function validate(mixed $value): bool;
}