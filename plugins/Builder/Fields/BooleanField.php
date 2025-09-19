<?php

namespace Plugins\Builder\Fields;

class BooleanField extends BaseField
{
    protected string $type = 'boolean';


    protected function performValidation(mixed $value): bool
    {
        return is_bool($value) || in_array($value, ['true', 'false', '1', '0', 1, 0], true);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }

    public function style(string $style): self
    {
        return $this->setAttribute('style', $style);
    }

    public function asToggle(): self
    {
        return $this->style('toggle');
    }

    public function asCheckbox(): self
    {
        return $this->style('checkbox');
    }

    public function asSwitch(): self
    {
        return $this->style('switch');
    }
}