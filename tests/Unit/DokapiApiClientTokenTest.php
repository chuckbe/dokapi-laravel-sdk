<?php

declare(strict_types=1);

namespace Dokapi\Tests\Unit;

use Dokapi\Api\DokapiApiClient;
use Dokapi\Tests\Fakes\RecordingHttpAdapter;
use Dokapi\Tests\TestCase;
use Illuminate\Support\Facades\Cache;

final class DokapiApiClientTokenTest extends TestCase
{
    private const OAUTH_ENDPOINT = 'https://oauth.test/token';

    private const REFRESH_TOKEN_CACHE_KEY = 'dokapi_api_refresh_token';

    /**
     * @test
     */
    public function it_falls_back_to_client_credentials_when_the_refresh_token_is_expired(): void
    {
        Cache::forever(self::REFRESH_TOKEN_CACHE_KEY, 'expired-refresh-token');

        $adapter = new RecordingHttpAdapter(self::OAUTH_ENDPOINT, refreshShouldFail: true);
        $client = $this->makeClient($adapter);

        $response = $client->performHttpCallToFullUrl(DokapiApiClient::HTTP_GET, 'https://api.test/v1/status');

        $this->assertTrue($response->ok);
        $this->assertSame(['refresh_token', 'client_credentials', 'api'], $adapter->calls);
        $this->assertSame('refresh-from-client-credentials', Cache::get(self::REFRESH_TOKEN_CACHE_KEY));
    }

    /**
     * @test
     */
    public function it_uses_the_refresh_token_when_it_is_still_valid(): void
    {
        Cache::forever(self::REFRESH_TOKEN_CACHE_KEY, 'valid-refresh-token');

        $adapter = new RecordingHttpAdapter(self::OAUTH_ENDPOINT, refreshShouldFail: false);
        $client = $this->makeClient($adapter);

        $response = $client->performHttpCallToFullUrl(DokapiApiClient::HTTP_GET, 'https://api.test/v1/status');

        $this->assertTrue($response->ok);
        $this->assertSame(['refresh_token', 'api'], $adapter->calls);
        $this->assertSame('valid-refresh-token', Cache::get(self::REFRESH_TOKEN_CACHE_KEY));
    }

    /**
     * @test
     */
    public function it_requests_client_credentials_when_no_refresh_token_is_cached(): void
    {
        $adapter = new RecordingHttpAdapter(self::OAUTH_ENDPOINT);
        $client = $this->makeClient($adapter);

        $response = $client->performHttpCallToFullUrl(DokapiApiClient::HTTP_GET, 'https://api.test/v1/status');

        $this->assertTrue($response->ok);
        $this->assertSame(['client_credentials', 'api'], $adapter->calls);
        $this->assertSame('refresh-from-client-credentials', Cache::get(self::REFRESH_TOKEN_CACHE_KEY));
    }

    private function makeClient(RecordingHttpAdapter $adapter): DokapiApiClient
    {
        return (new DokapiApiClient($adapter))
            ->setApiEndpoint('https://api.test')
            ->setOauth2ApiEndpoint(self::OAUTH_ENDPOINT)
            ->setClientId('client-id')
            ->setClientSecret('client-secret');
    }
}
