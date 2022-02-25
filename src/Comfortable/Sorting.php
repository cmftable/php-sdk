<?php declare(strict_types=1);

namespace Comfortable;

class Sorting
{
    private array $sorting = [];

    public function add(string $property, string $direction = 'ASC', string $context = 'fields'): self
    {
        $direction = (strtolower($direction) === "asc" || strtolower($direction) === "desc") ? strtolower($direction) : 'ASC';
        $this->sorting["$context.$property"] = $direction;

        return $this;
    }

    public function getSorting(): array
    {
        return $this->sorting;
    }
}
