<?php

namespace Plugins\Builder\Fields;

class SpacingField extends BaseField
{
    protected string $type = 'spacing';


    protected function performValidation(mixed $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        $uniform = $this->getAttribute('uniform', false);
        $min = $this->getAttribute('min', 0);
        $max = $this->getAttribute('max', 100);

        if ($uniform) {
            // Expect single value or 'all' key
            $val = is_array($value) ? ($value['all'] ?? 0) : $value;
            return is_numeric($val) && $val >= $min && $val <= $max;
        }

        // Expect top, right, bottom, left
        $required_keys = ['top', 'right', 'bottom', 'left'];
        foreach ($required_keys as $key) {
            if (!isset($value[$key]) || !is_numeric($value[$key])) {
                return false;
            }
            if ($value[$key] < $min || $value[$key] > $max) {
                return false;
            }
        }

        return true;
    }

    public function uniform(bool $uniform = true): self
    {
        return $this->setAttribute('uniform', $uniform);
    }

    public function min(int $min): self
    {
        return $this->setAttribute('min', $min);
    }

    public function max(int $max): self
    {
        return $this->setAttribute('max', $max);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}