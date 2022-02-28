<?php declare(strict_types=1);

namespace Comfortable\Traits;

use Comfortable\Sorting;

trait SortingTrait
{
    protected ?array $sorting = null;

    public function sorting(Sorting $sorting): self
    {
        $this->sorting = $sorting->getSorting();

        return $this;
    }

    public function getSorting(): ?array
    {
        return $this->sorting;
    }
}
