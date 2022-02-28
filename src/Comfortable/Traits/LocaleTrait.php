<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait LocaleTrait
{
    protected ?string $locale = null;

    public function locale(?string $locale = null): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }
}
