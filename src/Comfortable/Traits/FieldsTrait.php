<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait FieldsTrait
{
    protected ?string $fields = null;

    public function fields(?string $fields = null): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function getFields(): ?string
    {
        return $this->fields;
    }
}
