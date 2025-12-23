<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class OutgoingDocument extends BaseResource
{
    /**
     * The message of the outgoing Peppol document.
     *
     * @var string
     */
    public $message;

    /**
     * The document object of the outgoing Peppol document.
     *
     * @var Document
     */
    public $document;

    /**
     * The pre-signed upload url of the outgoing Peppol document.
     *
     * @var string
     */
    public $preSignedUploadUrl;

    public function document(object $document): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $document,
            new Document($this->client)
        );
    }
}
