<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait LocaleTrait
{
    /**
     * @var string|null $locale
     */
    protected $locale;

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
