<?php declare(strict_types=1);

namespace Comfortable;

use Comfortable\Traits\EmbedAssetsTrait;
use Comfortable\Traits\FieldsTrait;
use Comfortable\Traits\IncludeTrait;
use Comfortable\Traits\LocaleTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

class QueryDocument extends AbstractQuery
{
    use LocaleTrait;
    use IncludeTrait;
    use FieldsTrait;
    use EmbedAssetsTrait;

    /**
     * @var string $endpoint
     */
    protected $endpoint = 'documents';
    /**
     * @var string|null $entityId
     */
    protected $entityId;

    public function __construct(?string $entityId, $repository, ?Client $httpClient = null)
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
            throw new RuntimeException($e->getMessage());
        }

        return json_decode($response->getBody()->getContents(), false);
    }
}
