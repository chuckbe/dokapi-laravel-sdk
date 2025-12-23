<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

use Generator;
use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\DokapiApiClient;

abstract class CursorCollection extends BaseCollection
{
    /**
     * @var DokapiApiClient
     */
    protected $client;

    final public function __construct(DokapiApiClient $client, int $count, ?string $lastEvaluatedKey = null)
    {
        parent::__construct($count, $lastEvaluatedKey);

        $this->client = $client;
    }

    /**
     * @return BaseResource
     */
    abstract protected function createResourceObject();

    /**
     * Return the next set of resources when available.
     *
     * @throws ApiException
     */
    final public function next(string $apiPath): ?CursorCollection
    {
        if (!$this->hasNext()) {
            return null;
        }

        $filters = ['lastEvaluatedKey' => $this->lastEvaluatedKey, 'limit' => $this->count];

        $apiPath = $apiPath.'?'.http_build_query($filters, '', '&');

        $result = $this->client->performHttpCall(DokapiApiClient::HTTP_GET, $apiPath);

        /** @var CursorCollection $collection */
        $collection = new static(
            $this->client,
            property_exists($result, 'Count')
                ? $result->Count
                : count($result),
            property_exists($result, 'LastEvaluatedKey')
                ? $result->LastEvaluatedKey
                : null
        );

        foreach ($result->{$collection->getCollectionResourceName()} as $dataResult) {
            $collection[] = ResourceFactory::createFromApiResult($dataResult, $this->createResourceObject());
        }

        return $collection;
    }

    /**
     * Determine whether the collection has a next page available.
     */
    public function hasNext(): bool
    {
        return isset($this->lastEvaluatedKey)
            && null !== $this->lastEvaluatedKey;
    }

    /**
     * Iterate over a CursorCollection and yield its elements.
     *
     * @return LazyCollection<int, BaseResource>
     */
    public function getAutoIterator(string $apiPath): LazyCollection
    {
        $page = $this;

        return new LazyCollection(static function () use ($page, $apiPath): Generator {
            while (true) {
                foreach ($page as $item) {
                    yield $item;
                }

                if (!$page->hasNext()) {
                    break;
                }

                $page = $page->next($apiPath);
            }
        });
    }
}
