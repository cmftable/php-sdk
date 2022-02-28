<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait FieldsTrait
{
    /**
     * @var string|null $fields
     */
    protected $fields;

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
