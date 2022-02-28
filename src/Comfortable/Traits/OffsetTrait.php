<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait OffsetTrait
{
    /**
     * @var int $offset
     */
    protected $offset = 0;

    public function offset($offset = null): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
