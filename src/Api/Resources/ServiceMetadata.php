<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class ServiceMetadata extends BaseResource
{
    /**
     * The PEPPOL participant.
     *
     * @var ParticipantIdentifier
     */
    public $participantIdentifier;

    /**
     * The documentTypeIdentifier for a Peppol document.
     * See Peppol Code Lists → OutgoingDocument Types for a complete list.
     *
     * @var DocumentIdentifier
     */
    public $documentIdentifier;

    /**
     * The PEPPOL participant.
     *
     * @var ProcessCollection
     */
    public $processList;

    /**
     * The extension element may contain any XML element containing extra metadata about the service information type.
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

    public function documentIdentifier(object $documentIdentifier): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $documentIdentifier,
            new DocumentIdentifier($this->client)
        );
    }

    public function processList(object $processList): BaseCollection
    {
        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            Process::class,
            $processList->process
        );
    }
}
