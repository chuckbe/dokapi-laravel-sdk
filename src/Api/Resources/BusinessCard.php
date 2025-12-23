<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class BusinessCard extends BaseResource
{
    /**
     * The participant identifier of the business card.
     *
     * @var ParticipantIdentifier
     */
    public $participantIdentifier;

    /**
     * This is the unbounded list of business entities (companies or public administration bodies) that can be reached via the ServiceGroup.
     * The reason to allow 0 occurrences is to explicitly indicate that an SMP is capable of handling business cards but does not want to provide specific information for this Peppol participant.
     *
     * @var BusinessEntityCollection
     */
    public $businessEntity;

    public function participantIdentifier(object $participantIdentifier): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $participantIdentifier,
            new ParticipantIdentifier($this->client)
        );
    }

    /**
     * @param array<int, object> $businessEntity
     */
    public function businessEntity(array $businessEntity): BaseCollection
    {
        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            BusinessEntity::class,
            $businessEntity
        );
    }
}
