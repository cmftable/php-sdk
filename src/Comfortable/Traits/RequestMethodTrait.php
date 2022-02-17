<?php

namespace Comfortable\Traits;

trait RequestMethodTrait
{
    protected $method = 'GET';

    /**
     * @return \Comfortable\Traits\RequestMethodTrait
     */
    public function usePostMethod()
    {
        $this->method = 'POST';

        return $this;
    }

    /**
     * @return \Comfortable\Traits\RequestMethodTrait
     */
    public function useGetMethod()
    {
        $this->method = 'GET';

        return $this;
    }

    /**
     * @return boolean
     */
    public function isGet()
    {
        return $this->method === 'GET';
    }

    /**
     * @return boolean
     */
    public function isPost()
    {
        return $this->method === 'POST';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}
