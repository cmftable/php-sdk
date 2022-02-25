<?php declare(strict_types=1);

namespace Comfortable\Traits;

use Comfortable\Includer;

trait IncludeTrait
{
    protected array $include = [];
    protected ?int $includeLevel = null;

    public function includeByFields(Includer $include): self
    {
        $this->include = $include->getIncludeByFields();

        return $this;
    }

    public function includes(?int $includeLevel = null): self
    {
        $this->includeLevel = $includeLevel;

        return $this;
    }

    public function getIncludeByFields(): array
    {
        return $this->include;
    }

    public function getIncludeLevel(): ?int
    {
        return $this->includeLevel;
    }
}
