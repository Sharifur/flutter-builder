<?php

namespace Plugins\Builder\Fields;

class SelectField extends BaseField
{
    protected string $type = 'select';


    protected function performValidation(mixed $value): bool
    {
        $options = $this->getAttribute('options', []);
        $multiple = $this->getAttribute('multiple', false);

        if ($multiple && is_array($value)) {
            foreach ($value as $item) {
                if (!array_key_exists($item, $options)) {
                    return false;
                }
            }
            return true;
        }

        return array_key_exists($value, $options);
    }

    public function options(array $options): self
    {
        return $this->setAttribute('options', $options);
    }

    public function multiple(bool $multiple = true): self
    {
        return $this->setAttribute('multiple', $multiple);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}