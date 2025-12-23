<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class EndpointReferenceDto extends BaseResource
{
    /**
     * The URI for the endpoint address.
     *
     * @var string
     */
    public $address;
}
