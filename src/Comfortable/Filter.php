<?php declare(strict_types=1);

namespace Comfortable;

class Filter
{
    protected array $filter = [];

    /**
     * add a logical 'and' filter
     */
    public function addAnd(
        string $property,
        string $operator,
        $value,
        string $context = 'fields',
        string $contentType = '*'
    ): self
    {
        $filter = [
            "$contentType.$context.$property" => [
                $operator => $value,
            ],
        ];

        if (count($this->filter) === 0) {
            $this->filter[] = $filter;
        } else if (count($this->filter) > 0) {
            $this->filter[] = ["and" => $filter];
        }

        return $this;
    }

    /**
     * add a logical 'or' filter
     */
    public function addOr(
        string $property,
        string $operator,
        $value,
        string $context = 'fields',
        string $contentType = '*'): self
    {
        $filter = [
            "$contentType.$context.$property" => [
                $operator => $value,
            ],
        ];

        if (count($this->filter) === 0) {
            $this->filter[] = $filter;
        } elseif (count($this->filter) > 0) {
            $this->filter[] = ["or" => $filter];
        }

        return $this;
    }

    /**
     * return current filter
     */
    public function getFilter(): array
    {
        return $this->filter;
    }
}
