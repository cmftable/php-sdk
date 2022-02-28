<?php declare(strict_types=1);

namespace Comfortable;

abstract class AbstractQuery
{
    const API_ENDPOINT = "https://api.cmft.io/v1/";

    /**
     * @var string $repository
     */
    protected $repository;
    /**
     * @var \GuzzleHttp\Client $httpClient
     */
    protected $httpClient;
    /**
     * @var string $endpoint
     */
    protected $endpoint;
    /**
     * @var array $query
     */
    protected $query = [];

    /**
     * get Endpoint of specific resource
     */
    public function getEndpoint(string $entityId = null): string
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
     * @return bool|string
     */
    public function toJson()
    {
        return json_encode($this->query);
    }

    /**
     * return query object
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    abstract public function execute();
}
