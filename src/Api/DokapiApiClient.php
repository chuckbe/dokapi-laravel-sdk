<?php

declare(strict_types=1);

namespace Dokapi\Api;

use Dokapi\Api\Endpoints\IncomingDocumentEndpoint;
use Dokapi\Api\Endpoints\LookupEndpoint;
use Dokapi\Api\Endpoints\OutgoingDocumentEndpoint;
use Dokapi\Api\Endpoints\ParticipantRegistrationEndpoint;
use Dokapi\Api\Endpoints\StatusEndpoint;
use Dokapi\Api\Endpoints\WebhookEndpoint;
use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\HttpAdapter\DokapiHttpAdapterInterface;

final class DokapiApiClient
{
    /**
     * Version of the remote API.
     */
    public const API_VERSION = 'v1';

    /**
     * HTTP Methods.
     */
    public const HTTP_GET = 'GET';
    public const HTTP_POST = 'POST';
    public const HTTP_DELETE = 'DELETE';
    public const HTTP_PATCH = 'PATCH';
    public const HTTP_PUT = 'PUT';

    public const CACHE_REMEMBER_SECONDS = 3600;

    /**
     * @var DokapiHttpAdapterInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $apiEndpoint;

    /**
     * @var string
     */
    protected $oauth2ApiEndpoint;

    /**
     * RESTful Status resource.
     *
     * @var StatusEndpoint
     */
    public $status;

    /**
     * RESTful Outgoing OutgoingDocument resource.
     *
     * @var OutgoingDocumentEndpoint
     */
    public $outgoingDocuments;

    /**
     * RESTful Webhook resource.
     *
     * @var WebhookEndpoint
     */
    public $webhooks;

    /**
     * RESTful Webhook resource.
     *
     * @var ParticipantRegistrationEndpoint
     */
    public $participantRegistrations;

    /**
     * RESTful Webhook resource.
     *
     * @var LookupEndpoint
     */
    public $lookup;

    /**
     * RESTful Incoming OutgoingDocument resource.
     *
     * @var IncomingDocumentEndpoint
     */
    public $incomingDocuments;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $accessToken;

    protected string $accessTokenCacheKey = 'dokapi_api_access_token';

    protected string $refreshTokenCacheKey = 'dokapi_api_refresh_token';

    public function __construct(DokapiHttpAdapterInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        $this->initializeEndpoints();
    }

    public function initializeEndpoints(): void
    {
        $this->status = new StatusEndpoint($this);
        $this->outgoingDocuments = new OutgoingDocumentEndpoint($this);
        $this->webhooks = new WebhookEndpoint($this);
        $this->participantRegistrations = new ParticipantRegistrationEndpoint($this);
        $this->lookup = new LookupEndpoint($this);
        $this->incomingDocuments = new IncomingDocumentEndpoint($this);
    }

    public function setApiEndpoint(string $apiEndpoint): DokapiApiClient
    {
        $this->apiEndpoint = $apiEndpoint;

        return $this;
    }

    public function setOauth2ApiEndpoint(string $oauth2ApiEndpoint): DokapiApiClient
    {
        $this->oauth2ApiEndpoint = $oauth2ApiEndpoint;

        return $this;
    }

    public function setClientId(string $clientId): DokapiApiClient
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientSecret(string $clientSecret): DokapiApiClient
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    private function getAccessToken(): string
    {
        $refreshToken = $this->getRefreshToken();

        if (null === $refreshToken) {
            return $this->getAccessTokenFromApi();
        }

        return $this->refreshAccessToken($refreshToken);
    }

    private function setAccessToken(): void
    {
        $this->accessToken = cache()->remember($this->accessTokenCacheKey, self::CACHE_REMEMBER_SECONDS, function () {
            return $this->getAccessToken();
        });
    }

    private function getAccessTokenFromApi(): string
    {
        $httpMethod = self::HTTP_POST;
        $url = $this->oauth2ApiEndpoint;

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $httpBody = http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        $response = $this->httpClient->send($httpMethod, $url, $headers, $httpBody);

        $accessToken = $response->access_token;
        $refreshToken = $response->refresh_token;

        cache()->forget($this->refreshTokenCacheKey);
        cache()->forever($this->refreshTokenCacheKey, $refreshToken);

        return $accessToken;
    }

    private function refreshAccessToken(string $refreshToken): string
    {
        $httpMethod = self::HTTP_POST;
        $url = $this->oauth2ApiEndpoint;

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $httpBody = http_build_query([
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'refresh_token' => $refreshToken,
        ]);

        $response = $this->httpClient->send($httpMethod, $url, $headers, $httpBody);

        return $response->access_token;
    }

    private function getRefreshToken(): ?string
    {
        if (false === cache()->has($this->refreshTokenCacheKey)) {
            return null;
        }

        return cache()->get($this->refreshTokenCacheKey);
    }

    /**
     * Perform a http call. This method is used by the resource specific classes.
     *
     * @throws ApiException
     *
     * @return array<mixed>|object|null
     *
     * @codeCoverageIgnore
     */
    public function performHttpCall(
        string $httpMethod,
        string $apiMethod,
        ?string $httpBody = null,
        bool $expectString = false,
    ): array|object|null {
        $url = $this->apiEndpoint.'/'.self::API_VERSION.'/'.$apiMethod;

        return $this->performHttpCallToFullUrl($httpMethod, $url, $httpBody, $expectString);
    }

    /**
     * Perform a http call to a full url. This method is used by the resource specific classes.
     *
     * @throws ApiException
     *
     * @return array<mixed>|object|null
     *
     * @codeCoverageIgnore
     */
    public function performHttpCallToFullUrl(
        string $httpMethod,
        string $url,
        ?string $httpBody = null,
        bool $expectString = false,
    ): array|object|null {
        if (
            null === $this->apiEndpoint
            || '' === $this->apiEndpoint
            || null === $this->oauth2ApiEndpoint
            || '' === $this->oauth2ApiEndpoint
        ) {
            throw new ApiException('No endpoints set. Use setApiEndpoint() and setOauth2ApiEndpoint() to set them.');
        }

        if (
            null === $this->clientId
            || '' === $this->clientId
            || null === $this->clientSecret
            || '' === $this->clientSecret
        ) {
            throw new ApiException('No client ID or client secret set. Use setClientId() and setClientSecret() to set them.');
        }

        $this->setAccessToken();

        $userAgent = 'DokapiPeppolClient/1.0';

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$this->accessToken}",
            'User-Agent' => $userAgent,
        ];

        if (null !== $httpBody) {
            $headers['Content-Type'] = 'application/json';
        }

        $response = $this->httpClient->send($httpMethod, $url, $headers, $httpBody, $expectString);

        return $response;
    }
}
