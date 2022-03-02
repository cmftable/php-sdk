<?php

namespace Comfortable;

class Sorting
{
    /**
     * @var array
     */
    private $sorting = [];

    /**
     * @param string $property
     * @param string $direction
     * @param string $context
     *
     * @return \Comfortable\Sorting
     */
    public function add($property, $direction = 'ASC', $context = 'fields')
    {
        $direction = (strtolower($direction) === "asc" || strtolower($direction) === "desc") ? strtolower($direction) : 'ASC';
        $this->sorting["{$context}.{$property}"] = $direction;

        return $this;
    }

    /**
     * @return array
     */
    public function getSorting()
    {
        return $this->sorting;
    }
}
