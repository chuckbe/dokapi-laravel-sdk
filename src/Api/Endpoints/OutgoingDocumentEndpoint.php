<?php

declare(strict_types=1);

namespace Dokapi\Api\Endpoints;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\Resources\BaseResource;
use Dokapi\Api\Resources\OutgoingDocument;

final class OutgoingDocumentEndpoint extends EndpointAbstract
{
    /**
     * @var string
     */
    protected $resourcePath = 'outgoing-peppol-documents';

    protected function getResourceObject(): BaseResource
    {
        return new OutgoingDocument($this->client);
    }

    /**
     * This call initiates a new outgoing peppol document by specifying all required metadata for sending a Peppol message.
     *
     * @param array<mixed> $data
     * @param array<mixed> $filters
     *
     * @throws ApiException
     */
    public function create(array $data = [], array $filters = []): BaseResource
    {
        return $this->restCreate($data, $filters);
    }
}
