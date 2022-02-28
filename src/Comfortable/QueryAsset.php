<?php declare(strict_types=1);

namespace Comfortable;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

class QueryAsset extends AbstractQuery
{
    use Traits\LocaleTrait;
    use Traits\FieldsTrait;

    protected string $endpoint = 'assets';
    protected ?string $entityId;

    public function __construct(?string $entityId, $repository, ?Client $httpClient = null)
    {
        $this->entityId = $entityId;
        $this->httpClient = is_null($httpClient) ? new Client() : $httpClient;
        $this->repository = $repository;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function execute(): mixed
    {
        $queryParameters = [
            "query" => [],
        ];

        if ($this->getLocale()) {
            $this->query["locale"] = $this->getLocale();
        }

        if ($this->getFields()) {
            $this->query["fields"] = $this->getFields();
        }

        if (count($this->query) > 0) {
            $query = json_encode($this->query, JSON_THROW_ON_ERROR);
            $queryParameters["query"]["query"] = $query;
        }
        try {
            $response = $this->httpClient->request(
                'GET',
                $this->getEndpoint($this->entityId),
                $queryParameters
            );
        } catch (RequestException $e) {
            throw new RuntimeException($e->getMessage());
        }

        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }
}
