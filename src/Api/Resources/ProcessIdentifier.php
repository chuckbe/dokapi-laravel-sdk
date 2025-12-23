<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class ProcessIdentifier extends BaseResource
{
    /**
     * The optional scheme for this processIdentifier.
     * If none is given, the default scheme value will be used (cenbii-procid-ubl).
     *
     * @var string
     */
    public $scheme;

    /**
     * The mandatory value for this processIdentifier.
     * Example for an identifier that follow the cenbii-procid-ubl scheme: urn:fdc:peppol.eu:2017:poacc:billing:01:1.0.
     *
     * @var string
     */
    public $value;
}
