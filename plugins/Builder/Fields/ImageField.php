<?php

namespace Plugins\Builder\Fields;

class ImageField extends BaseField
{
    protected string $type = 'image';


    protected function performValidation(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Allow URLs or base64 data URLs
        return filter_var($value, FILTER_VALIDATE_URL) !== false ||
               str_starts_with($value, 'data:image/');
    }

    public function acceptedTypes(array $types): self
    {
        return $this->setAttribute('acceptedTypes', $types);
    }

    public function maxSize(int $sizeInKB): self
    {
        return $this->setAttribute('maxSize', $sizeInKB);
    }

    public function uploadUrl(string $url): self
    {
        return $this->setAttribute('uploadUrl', $url);
    }

    public function helpText(?string $helpText): self
    {
        return $this->setAttribute('helpText', $helpText);
    }
}