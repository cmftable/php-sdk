<?php

namespace Comfortable;

class Filter
{
    protected $filter = [];

    /**
     * add a logical 'and' filter
     *
     * @param string $property
     * @param string $operator
     * @param mixed $value
     * @param string $context
     * @param string $contentType
     *
     * @return \Comfortable\Filter
     */
    public function addAnd($property, $operator, $value, $context = 'fields', $contentType = '*')
    {
        $filter = [
            "$contentType.$context.$property" => [
                (string)$operator => $value,
            ],
        ];

        if (count($this->filter) === 0) {
            $this->filter[] = $filter;
        } elseif (count($this->filter) > 0) {
            $this->filter[] = ["and" => $filter];
        }

        return $this;
    }

    /**
     * add a logical 'or' filter
     *
     * @param string $property
     * @param string $operator
     * @param mixed $value
     * @param string $context
     * @param string $contentType
     *
     * @return \Comfortable\Filter
     */
    public function addOr($property, $operator, $value, $context = 'fields', $contentType = '*')
    {
        $filter = [
            "$contentType.$context.$property" => [
                (string)$operator => $value,
            ],
        ];

        if (count($this->filter) === 0) {
            $this->filter[] = $filter;
        } else if (sizeof($this->filter) > 0) {
            $this->filter[] = ["or" => $filter];
        }

        return $this;
    }

    /**
     * return current filter
     *
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }
}
