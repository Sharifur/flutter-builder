<?php

namespace Plugins\Builder\Fields;

class NumberField extends BaseField
{
    protected string $type = 'number';


    protected function performValidation(mixed $value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        $numValue = (float) $value;
        $min = $this->getAttribute('min');
        $max = $this->getAttribute('max');

        if ($min !== null && $numValue < $min) {
            return false;
        }

        if ($max !== null && $numValue > $max) {
            return false;
        }

        return true;
    }

    public function min(?int $min): self
    {
        return $this->setAttribute('min', $min);
    }

    public function max(?int $max): self
    {
        return $this->setAttribute('max', $max);
    }

    public function step(?float $step): self
    {
        return $this->setAttribute('step', $step);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}