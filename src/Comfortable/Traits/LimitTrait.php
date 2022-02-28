<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait LimitTrait
{
    protected ?int $limit = 25;

    public function limit(?int $limit = 25): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
