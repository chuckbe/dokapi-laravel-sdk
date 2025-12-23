<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class ServiceGroup extends BaseResource
{
    /**
     * The PEPPOL participant.
     *
     * @var ParticipantIdentifier
     */
    public $participantIdentifier;

    /**
     * The PEPPOL participant.
     *
     * @var ServiceMetadataReferenceCollection
     */
    public $serviceMetadataReferenceCollection;

    /**
     * The extension element may contain any XML element containing extra metadata about the service group.
     * Clients MAY ignore this element.
     *
     * @var string
     */
    public $extension;

    public function participantIdentifier(object $participantIdentifier): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $participantIdentifier,
            new ParticipantIdentifier($this->client)
        );
    }

    public function serviceMetadataReferenceCollection(object $serviceMetadataReferenceCollection): BaseCollection
    {
        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            ServiceMetadataReference::class,
            $serviceMetadataReferenceCollection->serviceMetadataReference
        );
    }
}
