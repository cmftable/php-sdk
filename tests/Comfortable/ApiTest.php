<?php

namespace Comfortable\Test;

use Comfortable;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase {
  protected $apiKey;
  protected $repository;

  protected function setUp() {
    $this->apiKey = $_ENV['CMFT_APIKEY'];
    $this->repository = $_ENV['CMFT_REPOSITORY'];
  }

  public function testMissingApiKey() {
    try {
      Comfortable\Api::connect($this->repository);
    } catch (\RuntimeException $e) {
      $this->assertInstanceOf(\RuntimeException::class, $e);
      $this->assertEquals($e->getMessage(), 'invalid apiKey');
    }
  }

  public function testInvalidApiKeyOrPermission() {
    try {
      Comfortable\Api::connect($this->repository, 'thisIsAnInvalidTestToken');
    } catch (\RuntimeException $e) {
      $this->assertInstanceOf(\RuntimeException::class, $e);
      $this->assertEquals($e->getMessage(), 'Invalid authentication credentials');
    }
  }

  public function testValidApiKey() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $this->assertInstanceOf(Comfortable\Api::class, $api);
  }

  public function testQueryBuilderExecution() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->query('documents')->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
  }

  public function testAllDocumentsWrapper() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
  }

  public function testGetAllDocumentsWithLimit() {
    $testValue = 1;
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()->limit($testValue)->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $this->assertEquals(sizeof($results->data), $testValue);
    $resultLimit = $results->meta->limit;
    $this->assertEquals($resultLimit, $testValue, "invalid limit. Expected: $testValue, given: $resultLimit");
  }

  public function testGetAllDocumentsWithOffset() {
    $testValue = 2;
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()->offset($testValue)->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $resultOffset = $results->meta->offset;
    $this->assertEquals($resultOffset, $testValue, "invalid offset. Expected: $testValue, given: $resultOffset");
  }

  public function testGetAllDocumentsWithSorting() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()
      ->sorting(
        (new Comfortable\Sorting)
          ->add('id', 'ASC', 'meta')
      )
      ->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $this->assertGreaterThanOrEqual($results->data[0]->meta->id, $results->data[1]->meta->id);
  }

  public function testGetAllDocumentsWithFilter() {
    $testValue = "100000";
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()
      ->filter(
        (new Comfortable\Filter)
          ->addAnd('id', 'greaterThan', $testValue, 'meta')
      )
      ->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    if (sizeof($results->data) > 0) {
      $this->assertGreaterThan($testValue, $results->data[0]->meta->id);
    }
  }

  public function testGetAllDocumentsWithIncludeByFields() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);

    $query = $api->getDocuments()
      ->includeByFields(
        (new Comfortable\Includer)
          ->add('relatedNews')
      );
    $results = $query->execute();

    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $this->assertInstanceOf(\stdClass::class, $results->includes, 'includes are missing or there are no relations');
    $this->assertGreaterThanOrEqual(1, sizeof($results->includes), 'there should be at least one related contentType');
  }

  public function testGetAllDocumentsWithIncludeLevel() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);

    $query = $api->getDocuments()
      ->includes(2);
    $results = $query->execute();

    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $this->assertInstanceOf(\stdClass::class, $results->includes, 'includes are missing or there are no relations');
    $this->assertGreaterThanOrEqual(1, sizeof($results->includes), 'there should be at least one related contentType');
  }

  public function testGetAllDocumentsWithSearch() {
    $testValue = "Test";
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()->search($testValue)->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
  }

  public function testGetAllDocumentsWithAllLocales() {
    $testValue = "all";
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()->locale($testValue)->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $this->assertInstanceOf(\stdClass::class, $results->data[0]->fields->title, 'there has to be a value for multiple languages');
    $this->assertInternalType('string', $results->data[0]->fields->title->de, 'there has to be a value for multiple languages');
  }

  public function testGetAllDocumentsWithIncludeTags() {
    $testValue = ["include"];
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()->includeTags($testValue)->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $this->assertContains($testValue[0], $results->data[0]->meta->tags, "must contains $testValue[0]");
  }

  public function testGetAllDocumentsWithExcludeTags() {
    $testValue = ["exclude"];
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()->excludeTags($testValue)->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $this->assertNotContains($testValue[0], $results->data[0]->meta->tags, "must contains $testValue[0]");
  }

  public function testGetAllDocumentsWithSpecificFields() {
    $testValue = 'fields(title)';
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocuments()->fields($testValue)->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
    $this->assertEquals(sizeof($results->data[0]->fields), 1, 'there should be only one field with apiId title');
  }

  public function testQueryBuilderGetCollection() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->query('collection', 'post')->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
  }

  public function testGetCollection() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getCollection('post')->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertEquals($results->status, 200);
    $this->assertInternalType('array', $results->data);
  }

  public function testGetDocument() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocument('982513777712959488')->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertInstanceOf(\stdClass::class, $results->fields);
    $this->assertInstanceOf(\stdClass::class, $results->meta);
  }

  public function testGetDocumentWithSpecificFields() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocument('982513777712959488')->fields('fields(title)')->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertInstanceOf(\stdClass::class, $results->fields);
  }

  public function testGetDocumentWithEmbeddedAssets() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getDocument('982513777712959488')->embedAssets(true)->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertInternalType('string', $results->fields->images[0]->fields->title);
  }

  public function testGetAlias() {
    $api = Comfortable\Api::connect($this->repository, $this->apiKey);
    $results = $api->getAlias('legalNotice')->execute();
    $this->assertInstanceOf(\stdClass::class, $results);
    $this->assertInstanceOf(\stdClass::class, $results->fields);
    $this->assertInstanceOf(\stdClass::class, $results->meta);
  }
}

?>