<?php

declare(strict_types=1);

namespace Dokapi;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Dokapi\Api\HttpAdapter\DokapiHttpClientAdapter;
use Dokapi\Api\DokapiApiClient;

final class DokapiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->doPublishing();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/dokapi.php',
            'dokapi'
        );

        $this->app->singleton(
            DokapiApiClient::class,
            static function (Container $app) {
                $client = (new DokapiApiClient(new DokapiHttpClientAdapter()));

                $apiEndpoint = $app['config']['dokapi.api_endpoint'];
                $oauth2ApiEndpoint = $app['config']['dokapi.oauth2_api_endpoint'];
                $clientId = $app['config']['dokapi.client_id'];
                $clientSecret = $app['config']['dokapi.client_secret'];

                if (
                    null !== $apiEndpoint
                    && '' !== $apiEndpoint
                ) {
                    $client->setApiEndpoint($apiEndpoint);
                }

                if (
                    null !== $oauth2ApiEndpoint
                    && '' !== $oauth2ApiEndpoint
                ) {
                    $client->setOauth2ApiEndpoint($oauth2ApiEndpoint);
                }

                if (
                    null !== $clientId
                    && '' !== $clientId
                ) {
                    $client->setClientId($clientId);
                }

                if (
                    null !== $clientSecret
                    && '' !== $clientSecret
                ) {
                    $client->setClientSecret($clientSecret);
                }

                return $client;
            }
        );

        $this->app->singleton(DokapiManager::class);
    }

    public function doPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/dokapi.php' => config_path('dokapi.php'),
        ], 'dokapi');
    }
}
