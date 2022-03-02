<?php

namespace Comfortable\Traits;

trait IncludeTagsTrait
{
    protected $includeTags;

    public function includeTags(array $tags)
    {
        $this->includeTags = $tags;
        return $this;
    }

    public function getIncludeTags()
    {
        return $this->includeTags;
    }
}
