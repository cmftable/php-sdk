<?php

namespace Comfortable\Traits;

trait EmbedAssetsTrait
{
    protected $embedAssets = false;

    public function embedAssets($embedAssets = null)
    {
        $this->embedAssets = $embedAssets;
        return $this;
    }

    public function getEmbedAssets()
    {
        return $this->embedAssets;
    }
}
