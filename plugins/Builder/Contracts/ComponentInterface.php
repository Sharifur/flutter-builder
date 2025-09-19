<?php

namespace Plugins\Builder\Contracts;

interface ComponentInterface
{
    public function getName(): string;

    public function getType(): string;

    public function getCategory(): string;

    public function getDescription(): string;

    public function getIcon(): ?string;

    public function getPreviewImage(): ?string;

    public function getDefaultConfig(): array;

    public function getFieldDefinitions(): array;

    public function getDependencies(): array;

    public function getSortOrder(): int;

    public function isActive(): bool;

    public function render(array $config): array;

    public function validate(array $config): bool;

    public function getConfigSchema(): array;
}