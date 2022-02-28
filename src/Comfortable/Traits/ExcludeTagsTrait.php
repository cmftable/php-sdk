<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait ExcludeTagsTrait
{
    /**
     * @var array $excludeTags
     */
    protected $excludeTags = [];

    public function excludeTags(array $tags): self
    {
        $this->excludeTags = $tags;

        return $this;
    }

    public function getExcludeTags(): array
    {
        return $this->excludeTags;
    }
}
