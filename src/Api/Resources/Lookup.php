<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class Lookup extends BaseResource
{
    /**
     * The service group of the participant registration.
     *
     * @var ServiceGroup
     */
    public $serviceGroup;

    /**
     * The service metadata; the participant identifier, plus the document identifier, plus the process list.
     *
     * @var ServiceMetadataCollection
     */
    public $serviceMetadata;

    /**
     * The participant identifier, plus the service metadata references.
     *
     * @var CompleteServiceGroup
     */
    public $completeServiceGroup;

    /**
     * The business card of the participant registration.
     *
     * @var BusinessCard
     */
    public $businessCard;

    public function serviceGroup(object $serviceGroup): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $serviceGroup,
            new ServiceGroup($this->client)
        );
    }

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

    public function completeServiceGroup(object $completeServiceGroup): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $completeServiceGroup,
            new CompleteServiceGroup($this->client)
        );
    }

    public function businessCard(object $businessCard): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $businessCard,
            new BusinessCard($this->client)
        );
    }
}
