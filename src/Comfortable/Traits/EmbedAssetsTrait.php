<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait EmbedAssetsTrait
{
    /**
     * @var bool $embedAssets
     */
    protected $embedAssets = false;

    public function embedAssets($embedAssets = null): self
    {
        $this->embedAssets = $embedAssets;

        return $this;
    }

    public function getEmbedAssets(): bool
    {
        return $this->embedAssets;
    }
}
