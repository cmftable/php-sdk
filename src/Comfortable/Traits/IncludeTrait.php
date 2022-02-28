<?php declare(strict_types=1);

namespace Comfortable\Traits;

use Comfortable\Includer;

trait IncludeTrait
{
    /**
     * @var array $include
     */
    protected $include = [];
    /**
     * @var int|null $includeLevel
     */
    protected $includeLevel;

    public function includeByFields(Includer $include): self
    {
        $this->include = $include->getIncludeByFields();

        return $this;
    }

    public function includes(int $includeLevel = null): self
    {
        $this->includeLevel = $includeLevel;

        return $this;
    }

    public function getIncludeByFields(): array
    {
        return $this->include;
    }

    public function getIncludeLevel()
    {
        return $this->includeLevel;
    }
}
