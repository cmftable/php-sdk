<?php

namespace Comfortable;

use Comfortable\Traits\EmbedAssetsTrait;
use Comfortable\Traits\FieldsTrait;
use Comfortable\Traits\IncludeTrait;
use Comfortable\Traits\LocaleTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class QueryAlias extends AbstractQuery
{
    use LocaleTrait;
    use IncludeTrait;
    use FieldsTrait;
    use EmbedAssetsTrait;

    protected $endpoint = 'alias';
    protected $entityId;

    public function __construct($entityId, $repository, Client $httpClient = null)
    {
        $this->entityId = $entityId;
        $this->httpClient = is_null($httpClient) ? new Client() : $httpClient;
        $this->repository = $repository;
    }

    public function execute()
    {
        $queryParameters = [
            "query" => [],
        ];

        if ($this->getLocale()) {
            $this->query["locale"] = $this->getLocale();
        }

        if ($this->getIncludeLevel()) {
            $this->query["includes"] = $this->getIncludeLevel();
        }

        if ($this->getIncludeByFields()) {
            $this->query["includes"] = $this->getIncludeByFields();
        }

        if ($this->getFields()) {
            $this->query["fields"] = $this->getFields();
        }

        if ($this->getEmbedAssets()) {
            $this->query["embedAssets"] = $this->getEmbedAssets();
        }

        if (count($this->query) > 0) {
            $query = json_encode($this->query);
            $queryParameters["query"]["query"] = $query;
        }

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->getEndpoint($this->entityId),
                $queryParameters
            );
        } catch (RequestException $e) {
            throw new \RuntimeException($e);
        }

        return json_decode($response->getBody()->getContents(), false);
    }
}
