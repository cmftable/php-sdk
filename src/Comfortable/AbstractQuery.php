<?php

namespace Comfortable;

abstract class AbstractQuery {
  const API_ENDPOINT = "https://api.cmft.io/v1/";

  /**
   * @var string
   */
  protected $repository;

  /**
   * @var Client
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
   * @param string $type
   * @param string $entityId
   * @return string
   */
  public function getEndpoint($entityId = null) {
    $urlArray = [self::API_ENDPOINT, $this->repository, '/', $this->endpoint, '/'];

    if ($entityId) {
      array_push($urlArray, "$entityId/");
    }

    return join($urlArray);
  }

  /**
   * return json encoded query
   *
   * @return void
   */
  public function toJson() {
    return json_encode($this->query);
  }

  /**
   * return query object
   *
   * @return void
   */
  public function getQuery() {
    return $this->query;
  }

  /** abstract */
  abstract public function execute();
}
?>
