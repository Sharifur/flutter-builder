<?php

namespace Plugins\Builder\Fields;

class ArrayField extends BaseField
{
    protected string $type = 'array';


    protected function performValidation(mixed $value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        $minItems = $this->getAttribute('minItems');
        $maxItems = $this->getAttribute('maxItems');

        if ($minItems !== null && count($value) < $minItems) {
            return false;
        }

        if ($maxItems !== null && count($value) > $maxItems) {
            return false;
        }

        return true;
    }

    public function itemType(string $type): self
    {
        return $this->setAttribute('itemType', $type);
    }

    public function minItems(int $min): self
    {
        return $this->setAttribute('minItems', $min);
    }

    public function maxItems(int $max): self
    {
        return $this->setAttribute('maxItems', $max);
    }

    public function sortable(bool $sortable = true): self
    {
        return $this->setAttribute('sortable', $sortable);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}