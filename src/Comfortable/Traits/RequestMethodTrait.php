<?php

namespace Comfortable\Traits;

trait RequestMethodTrait
{
    /**
     * @var string $method
     */
    protected $method = 'GET';

    public function usePostMethod():self
    {
        $this->method = 'POST';

        return $this;
    }

    public function useGetMethod():self
    {
        $this->method = 'GET';

        return $this;
    }

    public function isGet():bool
    {
        return $this->method === 'GET';
    }

    public function isPost():bool
    {
        return $this->method === 'POST';
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
