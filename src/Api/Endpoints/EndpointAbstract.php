<?php

declare(strict_types=1);

namespace Dokapi\Api\Endpoints;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\DokapiApiClient;
use Dokapi\Api\Resources\BaseResource;
use Dokapi\Api\Resources\ResourceFactory;

abstract class EndpointAbstract
{
    public const REST_CREATE = DokapiApiClient::HTTP_POST;
    public const REST_UPDATE = DokapiApiClient::HTTP_PATCH;
    public const REST_PUT = DokapiApiClient::HTTP_PUT;
    public const REST_READ = DokapiApiClient::HTTP_GET;
    public const REST_LIST = DokapiApiClient::HTTP_GET;
    public const REST_DELETE = DokapiApiClient::HTTP_DELETE;

    protected DokapiApiClient $client;

    /**
     * @var string
     */
    protected $resourcePath;

    public function __construct(DokapiApiClient $api)
    {
        $this->client = $api;
    }

    /**
     * @param array<mixed> $filters
     */
    protected function buildQueryString(array $filters): string
    {
        if (0 === count($filters)) {
            return '';
        }

        foreach ($filters as $key => $value) {
            if (true === $value) {
                $filters[$key] = 'true';
            }

            if (false === $value) {
                $filters[$key] = 'false';
            }
        }

        return '?'.http_build_query($filters, '', '&');
    }

    /**
     * @param array<mixed> $body
     * @param array<mixed> $filters
     *
     * @throws ApiException
     */
    protected function restCreate(array $body, array $filters): mixed
    {
        $result = $this->client->performHttpCall(
            self::REST_CREATE,
            $this->getResourcePath().$this->buildQueryString($filters),
            $this->parseRequestBody($body)
        );

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }

    /**
     * Sends a PATCH request to a single Dokapi API object.
     *
     * @param array<mixed> $body
     *
     * @throws ApiException
     */
    protected function restUpdate(string $id, array $body = []): mixed
    {
        if ('' === $id) {
            throw new ApiException('Invalid resource id.');
        }

        $id = urlencode($id);
        $result = $this->client->performHttpCall(
            self::REST_UPDATE,
            "{$this->getResourcePath()}/{$id}",
            $this->parseRequestBody($body)
        );

        if (null === $result) {
            return null;
        }

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }

    /**
     * Retrieves a single object from the Dokapi API.
     *
     * @param array<mixed> $filters
     *
     * @throws ApiException
     */
    protected function restRead(array $filters): mixed
    {
        $result = $this->client->performHttpCall(
            self::REST_READ,
            $this->getResourcePath().$this->buildQueryString($filters)
        );

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }

    /**
     * Sends a DELETE request to a single Dokapi API object.
     *
     * @param array<mixed> $body
     *
     * @throws ApiException
     */
    protected function restDelete(array $body = []): mixed
    {
        $result = $this->client->performHttpCall(
            self::REST_DELETE,
            $this->getResourcePath(),
            $this->parseRequestBody($body),
            true
        );

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }

    /**
     * Get the object that is used by this API endpoint. Every API endpoint uses one type of object.
     */
    abstract protected function getResourceObject(): BaseResource;

    public function setResourcePath(string $resourcePath): void
    {
        $this->resourcePath = mb_strtolower($resourcePath);
    }

    public function getResourcePath(): string
    {
        return $this->resourcePath;
    }

    /**
     * @param array<mixed> $body
     */
    protected function parseRequestBody(array $body): ?string
    {
        if (0 === count($body)) {
            return null;
        }

        return @json_encode($body);
    }
}
