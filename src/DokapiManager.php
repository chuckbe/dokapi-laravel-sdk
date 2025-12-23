<?php

declare(strict_types=1);

namespace Dokapi;

use Illuminate\Contracts\Container\Container;
use Dokapi\Api\DokapiApiClient;

final class DokapiManager
{
    public function __construct(private Container $app)
    {
    }

    public function api(): DokapiApiClient
    {
        return $this->app->make(DokapiApiClient::class);
    }
}
