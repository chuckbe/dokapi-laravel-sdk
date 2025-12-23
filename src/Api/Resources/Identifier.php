<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class Identifier extends BaseResource
{
    /**
     * The value of this identifier.
     *
     * @var string
     */
    public $value;

    /**
     * The scheme for this identifier.
     *
     * @var string
     */
    public $scheme;
}
