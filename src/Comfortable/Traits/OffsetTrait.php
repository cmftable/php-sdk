<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait OffsetTrait
{
    protected ?int $offset = 0;

    public function offset(?int $offset = null): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }
}
