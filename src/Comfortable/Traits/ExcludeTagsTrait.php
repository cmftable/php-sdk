<?php

namespace Comfortable\Traits;

trait ExcludeTagsTrait
{
    protected $excludeTags;

    public function excludeTags(array $tags)
    {
        $this->excludeTags = $tags;
        return $this;
    }

    public function getExcludeTags()
    {
        return $this->excludeTags;
    }
}
