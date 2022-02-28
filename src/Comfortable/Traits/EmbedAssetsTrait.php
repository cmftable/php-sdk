<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait EmbedAssetsTrait
{
    protected ?bool $embedAssets = false;

    public function embedAssets(?bool $embedAssets = null): self
    {
        $this->embedAssets = $embedAssets;

        return $this;
    }

    public function getEmbedAssets(): ?bool
    {
        return $this->embedAssets;
    }
}
