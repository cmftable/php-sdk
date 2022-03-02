<?php

namespace Comfortable;

/**
 * Includer
 */
class Includer
{
    protected $include = [];

    /** construct */
    public function __construct()
    {
    }

    /**
     * @param string $property
     * @param string $context
     * @param string $contentType
     *
     * @return self
     */
    public function add($property, $context = 'fields', $contentType = '*')
    {
        $this->include["$contentType.$context.$property"] = 1;

        return $this;
    }

    /**
     * @return array
     */
    public function getIncludeByFields()
    {
        return $this->include;
    }
}
