<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class Document extends BaseResource
{
    /**
     * The ulid of the document.
     *
     * @var string
     */
    public $ulid;

    /**
     * The status of the document.
     *
     * @var string
     */
    public $status;

    /**
     * The status message of the document.
     *
     * @var string
     */
    public $statusMessage;

    /**
     * The creation timestamp of the document.
     *
     * @var string
     */
    public $creationTimestamp;

    /**
     * The last modified timestamp of the document.
     *
     * @var string
     */
    public $lastModifiedTimestamp;

    /**
     * The sender object of the document.
     *
     * @var ParticipantIdentifier
     */
    public $sender;

    /**
     * The receiver object of the document.
     *
     * @var ParticipantIdentifier
     */
    public $receiver;

    /**
     * The country code of the sender of the document.
     *
     * @var string
     */
    public $c1CountryCode;

    /**
     * The document type identifier object of the document.
     *
     * @var DocumentIdentifier
     */
    public $documentTypeIdentifier;

    /**
     * The process identifier object of the document.
     *
     * @var ProcessIdentifier
     */
    public $processIdentifier;

    /**
     * The external reference of the document.
     *
     * @var string
     */
    public $externalReference;

    /**
     * The status history of the document.
     *
     * @var string
     */
    public $statusHistory;

    public function sender(object $sender): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $sender,
            new ParticipantIdentifier($this->client)
        );
    }

    public function receiver(object $receiver): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $receiver,
            new ParticipantIdentifier($this->client)
        );
    }

    public function documentTypeIdentifier(object $documentTypeIdentifier): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $documentTypeIdentifier,
            new DocumentIdentifier($this->client)
        );
    }

    public function processIdentifier(object $processIdentifier): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $processIdentifier,
            new ProcessIdentifier($this->client)
        );
    }
}
