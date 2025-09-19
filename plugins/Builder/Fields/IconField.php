<?php

namespace Plugins\Builder\Fields;

class IconField extends BaseField
{
    protected string $type = 'icon';


    protected function performValidation(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Basic validation - could be enhanced with actual icon set validation
        return !empty($value) && preg_match('/^[a-zA-Z0-9_-]+$/', $value);
    }

    public function iconSet(string $set): self
    {
        return $this->setAttribute('iconSet', $set);
    }

    public function categories(array $categories): self
    {
        return $this->setAttribute('categories', $categories);
    }

    public function searchable(bool $searchable = true): self
    {
        return $this->setAttribute('searchable', $searchable);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }

    // Predefined icon sets
    public static function materialIcons(): array
    {
        return [
            'home', 'star', 'favorite', 'settings', 'search', 'menu', 'close', 'check',
            'add', 'remove', 'edit', 'delete', 'share', 'download', 'upload', 'print',
            'email', 'phone', 'location', 'calendar', 'clock', 'person', 'group', 'chat'
        ];
    }
}