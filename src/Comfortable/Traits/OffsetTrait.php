<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait OffsetTrait
{
    protected $offset = 0;

    public function offset($offset = null)
    {
        $this->offset = $offset;
        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }
}
