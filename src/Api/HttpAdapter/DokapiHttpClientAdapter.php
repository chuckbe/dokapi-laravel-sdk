<?php

declare(strict_types=1);

namespace Dokapi\Api\HttpAdapter;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Dokapi\Api\Exceptions\ApiException;

final class DokapiHttpClientAdapter implements DokapiHttpAdapterInterface
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
    ): array|object|null {
        $contentType = $headers['Content-Type'] ?? 'application/json';
        unset($headers['Content-Type']);

        $response = Http::withBody($httpBody, $contentType)
            ->withHeaders($headers)
            ->send($httpMethod, $url);

        if (true === $response->noContent()) {
            return null;
        }

        if (true === $response->failed()) {
            throw ApiException::createFromResponse($response->toPsrResponse(), null);
        }

        if ('' === $response->body()) {
            throw new ApiException('Dokapi response body is empty.');
        }

        if (false !== $expectString) {
            return json_decode(json_encode(['message' => $response->body()]), false);
        }

        return $this->parseResponseBody($response);
    }

    /**
     * @throws ApiException
     *
     * @return array<mixed>|object|null
     */
    private function parseResponseBody(Response $response): array|object|null
    {
        $body = $response->body();

        $object = @json_decode($body);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new ApiException("Unable to decode Dokapi response: '{$body}'.");
        }

        return $object;
    }
}
