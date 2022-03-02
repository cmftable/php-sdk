<?php

namespace Comfortable\Traits;

trait SortingTrait
{
    protected $sorting;

    public function sorting(\Comfortable\Sorting $sorting)
    {
        $this->sorting = $sorting->getSorting();
        return $this;
    }

    public function getSorting()
    {
        return $this->sorting;
    }
}
