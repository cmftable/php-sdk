<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait SearchTrait
{
    protected ?string $search = null;

    public function search(?string $search = null): self
    {
        $this->search = $search;

        return $this;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }
}
