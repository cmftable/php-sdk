<?php declare(strict_types=1);

namespace Comfortable\Traits;

use Comfortable\Sorting;

trait SortingTrait
{
    protected $sorting;

    public function sorting(Sorting $sorting)
    {
        $this->sorting = $sorting->getSorting();
        return $this;
    }

    public function getSorting()
    {
        return $this->sorting;
    }
}
