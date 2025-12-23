<?php

declare(strict_types=1);

namespace Dokapi\Api\Endpoints;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\Resources\BaseCollection;
use Dokapi\Api\Resources\BaseResource;
use Dokapi\Api\Resources\CursorCollection;
use Dokapi\Api\Resources\LazyCollection;
use Dokapi\Api\Resources\ResourceFactory;

abstract class CollectionEndpointAbstract extends EndpointAbstract
{
    /**
     * Get a collection of objects from the API.
     *
     * @param string|null          $from    the first resource ID you want to include in your list
     * @param array<string, mixed> $filters
     *
     * @throws ApiException
     */
    protected function restList(?string $from = null, ?int $limit = null, array $filters = []): BaseCollection
    {
        $filters = array_merge(['lastEvaluatedKey' => $from, 'limit' => $limit], $filters);

        $apiPath = $this->getResourcePath().$this->buildQueryString($filters);

        $result = $this->client->performHttpCall(self::REST_LIST, $apiPath);

        /** @var BaseCollection $collection */
        $collection = $this->getResourceCollectionObject(
            property_exists($result, 'Count')
                ? $result->Count
                : count($result),
            property_exists($result, 'LastEvaluatedKey')
                ? $result->LastEvaluatedKey
                : null
        );

        foreach ($result->{$collection->getCollectionResourceName()} as $dataResult) {
            $collection[] = ResourceFactory::createFromApiResult($dataResult, $this->getResourceObject());
        }

        return $collection;
    }

    /**
     * Create a generator for iterating over a resource's collection using REST API calls.
     *
     * This function fetches paginated data from a resource endpoint and returns a generator
     * that allows you to iterate through the items in the collection one by one. It supports forward
     * and backward iteration, pagination, and filtering.
     *
     * @param ?string      $from    the first resource ID you want to include in your list
     * @param array<mixed> $filters
     *
     * @throws ApiException
     *
     * @return LazyCollection<int, BaseResource>
     */
    protected function restIterator(
        ?string $from = null,
        ?int $limit = null,
        array $filters = [],
    ): LazyCollection {
        /** @var CursorCollection $page */
        $page = $this->restList($from, $limit);

        return $page->getAutoIterator($this->getResourcePath());
    }

    /**
     * Get the collection object that is used by this API endpoint. Every API endpoint uses one type of collection object.
     */
    abstract protected function getResourceCollectionObject(int $count, ?string $lastEvaluatedKey = null): BaseCollection;
}
