<?php

declare(strict_types=1);

namespace Dokapi\Api\HttpAdapter;

use Dokapi\Api\Exceptions\ApiException;

interface DokapiHttpAdapterInterface
{
    /**
     * Send a request to the specified Dokapi api url.
     *
     * @param array<string, string>|string $headers
     *
     * @throws ApiException
     *
     * @return array<mixed>|object|null
     */
    public function send(
        string $httpMethod,
        string $url,
        array|string $headers,
        ?string $httpBody = null,
        bool $expectString = false,
    );
}
