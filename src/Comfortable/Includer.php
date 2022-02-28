<?php declare(strict_types=1);

namespace Comfortable;

class Includer
{
    protected array $include = [];

    public function add(string $property, string $context = 'fields', string $contentType = '*'): self
    {
        $this->include["$contentType.$context.$property"] = 1;

        return $this;
    }

    public function getIncludeByFields(): array
    {
        return $this->include;
    }
}
