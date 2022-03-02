<?php

namespace Comfortable\Traits;

trait SearchTrait
{
    protected $search;

    public function search($search = null)
    {
        $this->search = $search;
        return $this;
    }

    public function getSearch()
    {
        return $this->search;
    }
}
