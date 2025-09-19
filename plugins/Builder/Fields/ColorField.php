<?php

namespace Plugins\Builder\Fields;

class ColorField extends BaseField
{
    protected string $type = 'color';


    protected function performValidation(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Validate hex color format
        return preg_match('/^#[a-fA-F0-9]{6}$/', $value) === 1;
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}