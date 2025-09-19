<?php

namespace Plugins\Builder\Fields;

class TextField extends BaseField
{
    protected string $type = 'text';


    protected function performValidation(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $maxLength = $this->getAttribute('maxLength');
        if ($maxLength && strlen($value) > $maxLength) {
            return false;
        }

        return true;
    }

    public function placeholder(?string $placeholder): self
    {
        return $this->setAttribute('placeholder', $placeholder);
    }

    public function maxLength(?int $maxLength): self
    {
        return $this->setAttribute('maxLength', $maxLength);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}