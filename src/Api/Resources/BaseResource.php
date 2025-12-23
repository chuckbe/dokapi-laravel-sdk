<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

use AllowDynamicProperties;
use Dokapi\Api\DokapiApiClient;

#[AllowDynamicProperties]
abstract class BaseResource
{
    protected DokapiApiClient $client;

    public function __construct(DokapiApiClient $client)
    {
        $this->client = $client;
    }
}
