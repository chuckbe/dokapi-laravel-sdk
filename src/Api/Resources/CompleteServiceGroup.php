<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class CompleteServiceGroup extends BaseResource
{
    /**
     * The participant identifier, plus the service metadata references.
     *
     * @var ServiceGroup
     */
    public $serviceGroup;

    public function serviceGroup(object $serviceGroup): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $serviceGroup,
            new ServiceGroup($this->client)
        );
    }

    /**
     * The service metadata.
     *
     * @var ServiceMetadataCollection
     */
    public $serviceMetadata;

    /**
     * @param array<int, object> $serviceMetadata
     */
    public function serviceMetadata(array $serviceMetadata): BaseCollection
    {
        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            ServiceMetadata::class,
            $serviceMetadata
        );
    }
}
