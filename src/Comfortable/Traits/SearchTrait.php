<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait SearchTrait
{
    /**
     * @var string|null $search
     */
    protected $search;

    public function search($search = null): self
    {
        $this->search = $search;

        return $this;
    }

    public function getSearch()
    {
        return $this->search;
    }
}
