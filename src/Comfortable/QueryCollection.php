<?php declare(strict_types=1);

namespace Comfortable;

use Comfortable\Traits\EmbedAssetsTrait;
use Comfortable\Traits\ExcludeTagsTrait;
use Comfortable\Traits\FieldsTrait;
use Comfortable\Traits\FilterTrait;
use Comfortable\Traits\IncludeTagsTrait;
use Comfortable\Traits\IncludeTrait;
use Comfortable\Traits\LimitTrait;
use Comfortable\Traits\LocaleTrait;
use Comfortable\Traits\OffsetTrait;
use Comfortable\Traits\RequestMethodTrait;
use Comfortable\Traits\SearchTrait;
use Comfortable\Traits\SortingTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

class QueryCollection extends AbstractQuery
{
    use LocaleTrait;
    use LimitTrait;
    use OffsetTrait;
    use SortingTrait;
    use FilterTrait;
    use IncludeTrait;
    use SearchTrait;
    use IncludeTagsTrait;
    use ExcludeTagsTrait;
    use FieldsTrait;
    use EmbedAssetsTrait;
    use RequestMethodTrait;

    protected string $endpoint = 'collections';
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

        if (is_numeric($this->getLimit())) {
            $this->query["limit"] = $this->getLimit();
        }

        if (is_numeric($this->getOffset())) {
            $this->query["offset"] = $this->getOffset();
        }

        if ($this->getSorting()) {
            $this->query["sorting"] = $this->getSorting();
        }

        if ($this->getFilter()) {
            $this->query["filters"] = $this->getFilter();
        }

        if ($this->getIncludeLevel()) {
            $this->query["includes"] = $this->getIncludeLevel();
        }

        if ($this->getIncludeByFields()) {
            $this->query["includes"] = $this->getIncludeByFields();
        }

        if ($this->getSearch()) {
            $this->query["search"] = $this->getSearch();
        }

        if ($this->getIncludeTags()) {
            $this->query["includeTags"] = $this->getIncludeTags();
        }

        if ($this->getExcludeTags()) {
            $this->query["excludeTags"] = $this->getExcludeTags();
        }

        if ($this->getFields()) {
            $this->query["fields"] = $this->getFields();
        }

        if ($this->getEmbedAssets()) {
            $this->query["embedAssets"] = $this->getEmbedAssets();
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
