<?php declare(strict_types=1);

namespace Comfortable\Traits;

trait LocaleTrait
{
    protected $locale;

    public function locale($locale = null)
    {
        $this->locale = $locale;
        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }
}
