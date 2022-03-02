<?php

namespace Comfortable\Traits;

trait IncludeTrait
{
    protected $include;
    protected $includeLevel;

    public function includeByFields(\Comfortable\Includer $include)
    {
        $this->include = $include->getIncludeByFields();
        return $this;
    }

    public function getIncludeByFields()
    {
        return $this->include;
    }

    public function includes($includeLevel = null)
    {
        $this->includeLevel = $includeLevel;
        return $this;
    }

    public function getIncludeLevel()
    {
        return $this->includeLevel;
    }
}
