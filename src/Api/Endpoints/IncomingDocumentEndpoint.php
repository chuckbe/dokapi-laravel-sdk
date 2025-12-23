<?php

declare(strict_types=1);

namespace Dokapi\Api\Endpoints;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\Resources\BaseResource;
use Dokapi\Api\Resources\IncomingDocument;
use Dokapi\Api\Resources\ResourceFactory;

final class IncomingDocumentEndpoint extends EndpointAbstract
{
    /**
     * @var string
     */
    protected $resourcePath = 'incoming-peppol-documents';

    protected function getResourceObject(): BaseResource
    {
        return new IncomingDocument($this->client);
    }

    /**
     * Confirm the download of an incoming document.
     *
     * @throws ApiException
     */
    public function confirm(string $documentUlid): BaseResource
    {
        $apiMethod = "{$this->getResourcePath()}/{$documentUlid}/confirm";

        $result = $this->client->performHttpCall(
            httpMethod: self::REST_CREATE,
            apiMethod: $apiMethod,
            httpBody: null,
            expectString: true
        );

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }

    /**
     * Regenerate a presigned URL of the incoming document.
     *
     * @throws ApiException
     */
    public function generatePresignedUrl(string $documentUlid): BaseResource
    {
        $apiMethod = $this->getResourcePath()."/{$documentUlid}/generate-presigned-url";

        $result = $this->client->performHttpCall(
            self::REST_CREATE,
            $apiMethod,
            null,
            true
        );

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }
}
