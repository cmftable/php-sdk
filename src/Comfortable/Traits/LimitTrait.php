<?php

namespace Comfortable\Traits;

trait LimitTrait
{
    protected $limit = 25;

    public function limit($limit = 25)
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }
}
