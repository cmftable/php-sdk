<?php

namespace Comfortable;

use Comfortable\QueryBuilder;
use Comfortable\Sorting;
use Comfortable\Filter;
use Comfortable\Includer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;

/**
 *
 * Fetch data from Comfortable.io
 *
 * Usage:
 *  Comfortable\Api::connect(<repositoryId>, <apiKey>);
 */
class Api {
  /**
   * v1 api endpoint
   */
  const API_ENDPOINT = "https://api.cmft.io/v1/";

  /**
   * comfortable repositoryId
   *
   * @var string
   */
  protected $repository;

  /**
   * Api key which is used to call the api
   *
   * @var string
   */
  protected $apiKey;

  /**
   * http client
   *
   * @var Client
   */
  protected $httpClient;

  /**
   * repository base endpoint
   *
   * @var string
   */
  protected $url;

  /**
   * Custructor
   *
   * @param string $repository
   * @param string $apiKey
   * @param Client $httpClient
   */
  public function __construct($repository, $apiKey = null, Client $httpClient = null) {
    $this->repository = $repository;
    $this->apiKey = $apiKey;
    $this->httpClient = is_null($httpClient) ? new Client(['headers' => ['Authorization' => $apiKey]]) : $httpClient;
    $this->url = join([self::API_ENDPOINT, $repository, '/']);
  }

  /**
   * chose repository
   *
   * @param string $repository
   * @param string $apiKey
   * @param Client $httpClient
   * @return void
   */
  public static function connect($repository, $apiKey = null, $httpClient = null) {
    if (is_null($apiKey)) {
      throw new \RuntimeException('invalid apiKey');
    }

    $httpClient = is_null($httpClient) ? new Client(['headers' => ['Authorization' => $apiKey]]) : $httpClient;
    $url = join([self::API_ENDPOINT, $repository, '/']);

    try {
      $response = $httpClient->get($url);
    } catch(RequestException $e) {
      if ($e->hasResponse()) {
        $responseBody = $e->getResponse()->getBody();
        $responseBody = json_decode($responseBody);
        $error;

        switch($e->getResponse()->getStatusCode()){
          case 403:
          case 401:
            $error = $responseBody->message;
            break;
          default:
          $error = $responseBody->error->message;
        }

        throw new \RuntimeException($error);
      }
    }

    $api = new Api($repository, $apiKey, $httpClient);
    return $api;
  }

  /**
   * get api endpoint
   *
   * @return string
   */
  public static function getApiEndpoint() {
    return self::API_ENDPOINT;
  }

  /**
   * get base endpoint of the repository
   *
   * @return void
   */
  public function getRepositoryEndpoint() {
    return $this->url;
  }

  /**
   * Query all documents
   *
   * perform query against all documents
   *
   * @return void
   */
  public function getDocuments() {
    return $this->query('documents');
  }

  /**
   * Get single document
   *
   * @param string $id
   * @return void
   */
  public function getDocument($id = null) {
    return $this->query('document', $id);
  }

  /**
   * Get single asset
   *
   * @param string $id
   * @return void
   */
  public function getAsset($id = null) {
    return $this->query('asset', $id);
  }

  /**
   * Get document behind alias
   *
   * @param string $apiId
   * @return void
   */
  public function getAlias($apiId = null) {
    return $this->query('alias', $apiId);
  }

  /**
   * Query collection documents
   *
   * @param string $apiId
   * @return void
   */
  public function getCollection($apiId = null) {
    return $this->query('collection', $apiId);
  }

  /**
   * custom query
   *
   * @param string $resource
   * @return void
   */
  public function query($resource = null, $entityId = null) {
    switch($resource) {
      case "documents":
        return new QueryDocuments($this->repository, $this->httpClient);
        break;
      case "document":
        return new QueryDocument($entityId, $this->repository, $this->httpClient);
        break;
      case "asset":
        return new QueryAsset($entityId, $this->repository, $this->httpClient);
        break;
      case "alias":
        return new QueryAlias($entityId, $this->repository, $this->httpClient);
        break;
      case "collection":
      return new QueryCollection($entityId, $this->repository, $this->httpClient);
        break;
    }
  }
}
?>