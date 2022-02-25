<?php declare(strict_types=1);

namespace Comfortable\Test;

use Comfortable;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class ApiTest extends TestCase
{
    protected $apiKey;
    protected $repository;
    protected $documentId;
    protected $documentAlias;
    protected $assetId;
    protected $collectionApiId;

    protected function setUp(): void
    {
        $this->apiKey = getenv('CMFT_APIKEY');
        $this->repository = getenv('CMFT_REPOSITORY');
        $this->documentId = getenv('CMFT_DOCUMENT_ID');
        $this->documentAlias = getenv('CMFT_DOCUMENT_ALIAS');
        $this->assetId = getenv('CMFT_ASSET_ID');
        $this->collectionApiId = getenv('CMFT_COLLECTION_APIID');
    }

    public function testMissingApiKey(): void
    {
        try {
            Comfortable\Api::connect($this->repository);
        } catch (RuntimeException $e) {
            $this->assertInstanceOf(RuntimeException::class, $e);
            $this->assertEquals('invalid apiKey', $e->getMessage());
        } catch (\JsonException $e) {
        }
    }

    public function testInvalidApiKeyOrPermission(): void
    {
        try {
            Comfortable\Api::connect($this->repository, 'thisIsAnInvalidTestToken');
        } catch (RuntimeException $e) {
            $this->assertInstanceOf(RuntimeException::class, $e);
            $this->assertEquals('Invalid authentication credentials', $e->getMessage());
        }
    }

    public function testValidApiKey(): void
    {
        $this->assertInstanceOf(
            Comfortable\Api::class,
            Comfortable\Api::connect($this->repository, $this->apiKey)
        );
    }

    public function testQueryBuilderExecution(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->query('documents')->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
    }

    public function testAllDocumentsWrapper(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
    }

    public function testGetAllDocumentsWithLimit(): void
    {
        $testValue = 1;
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()->limit($testValue)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $this->assertEquals(count((array)$results->data), $testValue);
        $resultLimit = $results->meta->limit;
        $this->assertEquals($resultLimit, $testValue, "invalid limit. Expected: $testValue, given: $resultLimit");
    }

    public function testGetAllDocumentsWithOffset(): void
    {
        $testValue = 2;
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()->offset($testValue)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $resultOffset = $results->meta->offset;
        $this->assertEquals($resultOffset, $testValue, "invalid offset. Expected: $testValue, given: $resultOffset");
    }

    public function testGetAllDocumentsWithSorting(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()
            ->sorting(
                (new Comfortable\Sorting)->add('id', 'ASC', 'meta')
            )
            ->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $this->assertGreaterThanOrEqual($results->data[0]->meta->id, $results->data[1]->meta->id);
    }

    public function testGetAllDocumentsWithFilter(): void
    {
        $testValue = "100000";
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()
            ->filter(
                (new Comfortable\Filter)->addAnd('id', 'greaterThan', $testValue, 'meta')
            )
            ->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        if (sizeof((array)$results->data) > 0) {
            $this->assertGreaterThan($testValue, $results->data[0]->meta->id);
        }
    }

    public function testGetAllDocumentsWithIncludeByFields(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);

        $query = $api->getDocuments()
            ->includeByFields(
                (new Comfortable\Includer)->add('relatedNews')
            );
        $results = $query->execute();

        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $this->assertInstanceOf(stdClass::class, $results->includes, 'includes are missing or there are no relations');
        $this->assertGreaterThanOrEqual(1, sizeof((array)$results->includes), 'there should be at least one related contentType');
    }

    public function testGetAllDocumentsWithIncludeLevel()
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);

        $query = $api->getDocuments()->includes(2);
        $results = $query->execute();

        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $this->assertInstanceOf(stdClass::class, $results->includes, 'includes are missing or there are no relations');
        $this->assertGreaterThanOrEqual(1, sizeof((array)$results->includes), 'there should be at least one related contentType');
    }

    public function testGetAllDocumentsWithSearch(): void
    {
        $testValue = "Test";
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()->search($testValue)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
    }

    public function testGetAllDocumentsWithAllLocales(): void
    {
        $testValue = "all";
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()->locale($testValue)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $this->assertInstanceOf(
            stdClass::class, $results->data[0]->fields->title,
            'there has to be a value for multiple languages'
        );
        $this->assertIsString(
            $results->data[0]->fields->title->de,
            'there has to be a value for multiple languages'
        );
    }

    public function testGetAllDocumentsWithIncludeTags(): void
    {
        $testValue = ["include"];
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()->includeTags($testValue)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $this->assertContains($testValue[0], $results->data[0]->meta->tags, "must contains $testValue[0]");
    }

    public function testGetAllDocumentsWithExcludeTags(): void
    {
        $testValue = ["exclude"];
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()->excludeTags($testValue)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $this->assertNotContains($testValue[0], $results->data[0]->meta->tags, "must contains $testValue[0]");
    }

    public function testGetAllDocumentsWithSpecificFields(): void
    {
        $testValue = 'fields(title)';
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocuments()->fields($testValue)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
        $this->assertCount(
            1,
            (array)$results->data[0]->fields,
            'there should be only one field with apiId title'
        );
    }

    public function testQueryBuilderGetCollection(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->query('collection', $this->collectionApiId)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
    }

    public function testGetCollection(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getCollection('post')->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertEquals(200, $results->status);
        $this->assertIsArray($results->data);
    }

    public function testGetDocument(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocument($this->documentId)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertInstanceOf(stdClass::class, $results->fields);
        $this->assertInstanceOf(stdClass::class, $results->meta);
    }

    public function testGetDocumentWithSpecificFields(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocument($this->documentId)->fields('fields(title)')->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertInstanceOf(stdClass::class, $results->fields);
    }

    public function testGetDocumentWithEmbeddedAssets(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getDocument($this->documentId)->embedAssets(true)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertIsString($results->fields->images[0]->fields->title);
    }

    public function testGetAlias(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getAlias($this->documentAlias)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertInstanceOf(stdClass::class, $results->fields);
        $this->assertInstanceOf(stdClass::class, $results->meta);
    }

    public function testGetAsset(): void
    {
        $api = Comfortable\Api::connect($this->repository, $this->apiKey);
        $results = $api->getAsset($this->assetId)->execute();
        $this->assertInstanceOf(stdClass::class, $results);
        $this->assertInstanceOf(stdClass::class, $results->fields);
        $this->assertInstanceOf(stdClass::class, $results->meta);
    }
}
