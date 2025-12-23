<?php

declare(strict_types=1);

namespace Dokapi\Api\Endpoints;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\Resources\BaseCollection;
use Dokapi\Api\Resources\BaseResource;
use Dokapi\Api\Resources\ResourceFactory;
use Dokapi\Api\Resources\Status;
use Dokapi\Api\Resources\Webhook;
use Dokapi\Api\Resources\WebhookCollection;

final class WebhookEndpoint extends CollectionEndpointAbstract
{
    /**
     * @var string
     */
    protected $resourcePath = 'webhooks';

    protected function getResourceObject(): BaseResource
    {
        return new Webhook($this->client);
    }

    protected function getResourceCollectionObject(int $count, ?string $lastEvaluatedKey = null): BaseCollection
    {
        return new WebhookCollection($count, $lastEvaluatedKey);
    }

    /**
     * Retrieve all webhooks.
     *
     * @throws ApiException
     */
    public function all(): BaseCollection
    {
        $result = $this->client->performHttpCall('GET', $this->resourcePath);

        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            Webhook::class,
            $result
        );
    }

    /**
     * Register a webhook within SMP via the Dokapi SMP client.
     *
     * @param array<mixed> $data
     * @param array<mixed> $filters
     *
     * @throws ApiException
     */
    public function create(array $data = [], array $filters = []): BaseResource
    {
        $result = $this->client->performHttpCall(
            self::REST_CREATE,
            $this->getResourcePath().$this->buildQueryString($filters),
            $this->parseRequestBody($data)
        );

        return ResourceFactory::createFromApiResult($result->webhook, $this->getResourceObject());
    }

    /**
     * Deregister a participant within SMP via the Dokapi SMP client.
     *
     * @throws ApiException
     */
    public function delete(string $ulid): ?BaseResource
    {
        if (empty($ulid)) {
            throw new ApiException('Invalid resource id.');
        }

        $ulid = urlencode($ulid);
        $result = $this->client->performHttpCall(
            self::REST_DELETE,
            "{$this->getResourcePath()}/{$ulid}"
        );

        if (null === $result) {
            return null;
        }

        return ResourceFactory::createFromApiResult($result, new Status($this->client));
    }

    /**
     * Generate a webhook secret as a client.
     * In case you need a new key, rerun this operation to invalidate the previous key and regenerate a new one.
     */
    public function secret(): BaseResource
    {
        $result = $this->client->performHttpCall(
            self::REST_CREATE,
            "{$this->getResourcePath()}/secretKey",
            null,
            true
        );

        return ResourceFactory::createFromApiResult($result, new Status($this->client));
    }
}
