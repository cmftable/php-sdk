<?php declare(strict_types=1);

namespace Comfortable;

use GuzzleHttp\Client;

abstract class AbstractQuery
{
    public const API_ENDPOINT = "https://api.cmft.io/v1/";

    protected string $repository;
    protected Client $httpClient;
    protected string $endpoint;
    protected array $query = [];

    /**
     * get Endpoint of specific resource
     */
    public function getEndpoint(?string $entityId = null): string
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
     *
     * @throws \JsonException
     */
    public function toJson(): ?bool
    {
        return json_encode($this->query, JSON_THROW_ON_ERROR);
    }

    /**
     * return query object
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    abstract public function execute(): mixed;
}
