<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait IncludeTagsTrait
{
    /**
     * @var array $includeTags
     */
    protected $includeTags = [];

    public function includeTags(array $tags): self
    {
        $this->includeTags = $tags;

        return $this;
    }

    public function getIncludeTags(): array
    {
        return $this->includeTags;
    }
}
