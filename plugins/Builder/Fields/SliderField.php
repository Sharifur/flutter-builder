<?php

namespace Plugins\Builder\Fields;

class SliderField extends BaseField
{
    protected string $type = 'slider';


    protected function performValidation(mixed $value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        $numValue = (float) $value;
        $min = $this->getAttribute('min', 0);
        $max = $this->getAttribute('max', 100);

        return $numValue >= $min && $numValue <= $max;
    }

    public function min(int|float $min): self
    {
        return $this->setAttribute('min', $min);
    }

    public function max(int|float $max): self
    {
        return $this->setAttribute('max', $max);
    }

    public function step(int|float $step): self
    {
        return $this->setAttribute('step', $step);
    }

    public function showValue(bool $show = true): self
    {
        return $this->setAttribute('showValue', $show);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}