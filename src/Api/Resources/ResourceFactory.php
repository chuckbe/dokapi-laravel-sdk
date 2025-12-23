<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

use AllowDynamicProperties;
use Dokapi\Api\DokapiApiClient;

#[AllowDynamicProperties]
final class ResourceFactory
{
    /**
     * Create resource object from Api result.
     */
    public static function createFromApiResult(object $apiResult, BaseResource $resource): BaseResource
    {
        foreach ($apiResult as $property => $value) {
            if (method_exists($resource, $property)) {
                $resource->{$property} = $resource->{$property}($value);

                continue;
            }

            $resource->{$property} = $value;
        }

        return $resource;
    }

    /**
     * @param array<mixed> $data
     */
    public static function createBaseResourceCollection(
        DokapiApiClient $client,
        string $resourceClass,
        array $data,
        ?string $resourceCollectionClass = null,
    ): BaseCollection {
        $resourceCollectionClass = $resourceCollectionClass ?: $resourceClass.'Collection';
        $data = $data ?: [];

        $result = new $resourceCollectionClass(count($data));
        foreach ($data as $item) {
            if (null !== $result->getCollectionResourceName()) {
                $item = $item->{$result->getCollectionResourceName()};
            }

            $result[] = static::createFromApiResult($item, new $resourceClass($client));
        }

        return $result;
    }
}
