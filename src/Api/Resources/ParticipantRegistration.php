<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class ParticipantRegistration extends BaseResource
{
    /**
     * The ulid of the participant registration in the Dokapi database. Can be used in case of errors.
     *
     * @var string
     */
    public $ulid;

    /**
     * The country code.
     *
     * @var string
     */
    public $countryCode;

    /**
     * The date the participant was created.
     *
     * @var string
     */
    public $creationTimestamp;

    /**
     * The last time the participant was modified.
     *
     * @var string
     */
    public $lastModifiedTimestamp;

    /**
     * The Peppol participant we want to register.
     *
     * @var ParticipantIdentifier
     */
    public $participantIdentifier;

    public function participantIdentifier(object $participantIdentifier): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $participantIdentifier,
            new ParticipantIdentifier($this->client)
        );
    }
}
