<?php

namespace Plugins\Builder\Fields;

class TextareaField extends BaseField
{
    protected string $type = 'textarea';


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

    public function rows(int $rows): self
    {
        return $this->setAttribute('rows', $rows);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}