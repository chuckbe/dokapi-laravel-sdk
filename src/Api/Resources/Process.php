<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class Process extends BaseResource
{
    /**
     * The processIdentifier for a Peppol document.
     * See Peppol Code Lists → Processes for a complete list.
     *
     * @var ProcessIdentifier
     */
    public $processIdentifier;

    /**
     * List of one or more endpoints that support this process.
     *
     * @var EndpointCollection
     */
    public $serviceEndpointList;

    /**
     * The extension element may contain any XML element containing extra metadata about the process type.
     * Clients MAY ignore this element.
     *
     * @var string
     */
    public $extension;

    public function processIdentifier(object $processIdentifier): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $processIdentifier,
            new ProcessIdentifier($this->client)
        );
    }

    public function serviceEndpointList(object $serviceEndpointList): BaseCollection
    {
        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            Endpoint::class,
            $serviceEndpointList->endpoint
        );
    }
}
