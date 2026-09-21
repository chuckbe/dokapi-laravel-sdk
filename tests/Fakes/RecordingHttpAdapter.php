<?php

declare(strict_types=1);

namespace Dokapi\Tests\Fakes;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\HttpAdapter\DokapiHttpAdapterInterface;
use stdClass;

final class RecordingHttpAdapter implements DokapiHttpAdapterInterface
{
    /**
     * @var array<int, string>
     */
    public array $calls = [];

    public function __construct(
        private string $oauthEndpoint,
        private bool $refreshShouldFail = false,
        private string $accessTokenFromRefresh = 'access-from-refresh',
        private string $accessTokenFromClientCredentials = 'access-from-client-credentials',
        private string $refreshTokenFromClientCredentials = 'refresh-from-client-credentials',
    ) {
    }

    /**
     * @param array<string, string>|string $headers
     *
     * @throws ApiException
     */
    public function send(
        string $httpMethod,
        string $url,
        array|string $headers,
        ?string $httpBody = null,
        bool $expectString = false,
    ): array|object|null {
        if ($url !== $this->oauthEndpoint) {
            $this->calls[] = 'api';

            return $this->object(['ok' => true]);
        }

        $body = (string) $httpBody;

        if (str_contains($body, 'grant_type=refresh_token')) {
            $this->calls[] = 'refresh_token';

            if ($this->refreshShouldFail) {
                throw new ApiException('invalid_grant', 400);
            }

            return $this->object(['access_token' => $this->accessTokenFromRefresh]);
        }

        $this->calls[] = 'client_credentials';

        return $this->object([
            'access_token' => $this->accessTokenFromClientCredentials,
            'refresh_token' => $this->refreshTokenFromClientCredentials,
        ]);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    private function object(array $attributes): stdClass
    {
        $object = new stdClass();

        foreach ($attributes as $key => $value) {
            $object->{$key} = $value;
        }

        return $object;
    }
}
