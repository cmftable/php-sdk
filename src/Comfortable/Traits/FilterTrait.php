<?php declare(strict_types=1);

namespace Comfortable\Traits;

use Comfortable\Filter;

trait FilterTrait
{
    protected array $filter = [];

    public function filter(Filter $filter): self
    {
        $this->filter = $filter->getFilter();

        return $this;
    }

    public function getFilter(): array
    {
        return $this->filter;
    }
}
