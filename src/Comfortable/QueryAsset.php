<?php declare(strict_types=1);

namespace Comfortable;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

class QueryAsset extends AbstractQuery
{
    use Traits\LocaleTrait;
    use Traits\FieldsTrait;

    /**
     * @var string $endpoint
     */
    protected $endpoint = 'assets';
    /**
     * @var string|null $entityId
     */
    protected $entityId;

    public function __construct($entityId, $repository, Client $httpClient = null)
    {
        $this->entityId = $entityId;
        $this->httpClient = is_null($httpClient) ? new Client() : $httpClient;
        $this->repository = $repository;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute()
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
            throw new RuntimeException($e->getMessage());
        }

        return json_decode($response->getBody()->getContents(), false);
    }
}
