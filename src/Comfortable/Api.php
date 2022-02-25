<?php declare(strict_types=1);

namespace Comfortable;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use RuntimeException;

/**
 * Fetch data from Comfortable.io
 *
 * Usage:
 *  Comfortable\Api::connect(<repositoryId>, <apiKey>);
 */
class Api
{
    /**
     * v1 api endpoint
     */
    public const API_ENDPOINT = "https://api.cmft.io/v1/";

    /**
     * comfortable repositoryId
     */
    protected string $repository;

    /**
     * Api key which is used to call the api
     */
    protected ?string $apiKey;

    /**
     * http client
     */
    protected Client $httpClient;

    /**
     * repository base endpoint
     */
    protected string $url;

    public function __construct(string $repository, string $apiKey = null, Client $httpClient = null)
    {
        $this->repository = $repository;
        $this->apiKey = $apiKey;
        $this->httpClient = is_null($httpClient) ? new Client(['headers' => ['Authorization' => $apiKey]]) : $httpClient;
        $this->url = join([self::API_ENDPOINT, $repository, '/']);
    }

    /**
     * chose repository
     */
    public static function connect(string $repository, string $apiKey = null, Client $httpClient = null): Api
    {
        if (is_null($apiKey)) {
            throw new RuntimeException('invalid apiKey');
        }

        $httpClient = is_null($httpClient) ? new Client(['headers' => ['Authorization' => $apiKey]]) : $httpClient;
        $url = implode([self::API_ENDPOINT, $repository, '/']);

        try {
            $httpClient->get($url);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $responseBody = $e->getResponse()->getBody();
                $responseBody = json_decode((string)$responseBody, false, 512, JSON_THROW_ON_ERROR);

                switch ($e->getResponse()->getStatusCode()) {
                    case 403:
                    case 401:
                        $error = $responseBody->message;
                        break;
                    default:
                        $error = $responseBody->error->message;
                }

                throw new RuntimeException($error);
            }
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage());
        }

        return new Api($repository, $apiKey, $httpClient);
    }

    /**
     * get api endpoint
     */
    public static function getApiEndpoint(): string
    {
        return self::API_ENDPOINT;
    }

    /**
     * get base endpoint of the repository
     */
    public function getRepositoryEndpoint(): string
    {
        return $this->url;
    }

    /**
     * Query all documents
     *
     * perform query against all documents
     */
    public function getDocuments(): AbstractQuery
    {
        return $this->query('documents');
    }

    /**
     * custom query
     *
     * @throws \Exception
     */
    public function query(?string $resource = null, ?string $entityId = null): AbstractQuery
    {
        switch ($resource) {
            case 'documents':
                $query = new QueryDocuments($this->repository, $this->httpClient);
                break;
            case 'document':
                $query = new QueryDocument($entityId, $this->repository, $this->httpClient);
                break;
            case 'asset':
                $query = new QueryAsset($entityId, $this->repository, $this->httpClient);
                break;
            case 'alias':
                $query = new QueryAlias($entityId, $this->repository, $this->httpClient);
                break;
            case 'collection':
                $query = new QueryCollection($entityId, $this->repository, $this->httpClient);
                break;
            default:
                throw new \RuntimeException('');
        }

        return $query;
    }

    /**
     * Get single document
     *
     * @throws \Exception
     */
    public function getDocument(string $id = null): AbstractQuery
    {
        return $this->query('document', $id);
    }

    /**
     * Get single asset
     *
     * @throws \Exception
     */
    public function getAsset(string $id = null): AbstractQuery
    {
        return $this->query('asset', $id);
    }

    /**
     * Get document behind alias
     *
     * @throws \Exception
     */
    public function getAlias(string $apiId = null): AbstractQuery
    {
        return $this->query('alias', $apiId);
    }

    /**
     * Query collection documents
     *
     * @throws \Exception
     */
    public function getCollection(?string $apiId = null): AbstractQuery
    {
        return $this->query('collection', $apiId);
    }
}
