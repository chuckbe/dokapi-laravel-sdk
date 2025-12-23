<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class ParticipantIdentifier extends BaseResource
{
    /**
     * The scheme for this participant identifier.
     *
     * @var string
     */
    public $scheme;

    /**
     * The value for this participant identifier.
     *
     * @var string
     */
    public $value;
}
