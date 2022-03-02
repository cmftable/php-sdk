<?php

namespace Comfortable;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class QueryCollection extends AbstractQuery {
  use Traits\LocaleTrait;
  use Traits\LimitTrait;
  use Traits\OffsetTrait;
  use Traits\SortingTrait;
  use Traits\FilterTrait;
  use Traits\IncludeTrait;
  use Traits\SearchTrait;
  use Traits\IncludeTagsTrait;
  use Traits\ExcludeTagsTrait;
  use Traits\FieldsTrait;
  use Traits\EmbedAssetsTrait;
  use Traits\RequestMethodTrait;

  protected $endpoint = 'collections';
  protected $entityId;

  public function __construct($entityId, $repository, Client $httpClient = null) {
    $this->entityId = $entityId;
    $this->httpClient = is_null($httpClient) ? new Client() : $httpClient;
    $this->repository = $repository;
  }

  public function execute(){
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

    $options = [];
    $entityId = $this->entityId;
    if ($this->isPost()) {
      $options['headers'] = ['Content-Type' => 'application/json'];
      $options["json"] = $this->query;
      $entityId .= ($entityId ? '' : '/blogPosts');
    } else {
      if (sizeof($this->query) > 0) {
        $options["query"]["query"] = json_encode($this->query);
      }
    }

    try {
      $response = $this->httpClient
          ->request(
              $this->getMethod(),
              $this->getEndpoint($entityId),
              $options
          );
    } catch(RequestException $e) {
      throw new \RuntimeException($e);
    }

    return json_decode($response->getBody());
  }
}
?>
