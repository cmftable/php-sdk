<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait LocaleTrait
{
    /**
     * @var string|null $locale
     */
    protected $locale;

    public function locale($locale = null): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }
}
