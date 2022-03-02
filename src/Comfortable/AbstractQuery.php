<?php

namespace Comfortable;

abstract class AbstractQuery
{
    const API_ENDPOINT = "https://api.cmft.io/v1/";

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var array
     */
    protected $query = [];

    /**
     * get Endpoint of specific resource
     *
     * @param string $entityId
     *
     * @return string
     */
    public function getEndpoint($entityId = null)
    {
        $urlArray = [self::API_ENDPOINT, $this->repository, '/', $this->endpoint, '/'];

        if ($entityId) {
            $urlArray[] = "$entityId/";
        }

        return implode($urlArray);
    }

    /**
     * return json encoded query
     *
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->query);
    }

    /**
     * return query object
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /** abstract */
    abstract public function execute();
}
