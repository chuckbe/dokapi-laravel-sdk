<?php

declare(strict_types=1);

namespace Dokapi\Api\Endpoints;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\Resources\BaseResource;
use Dokapi\Api\Resources\ResourceFactory;
use Dokapi\Api\Resources\Status;

final class StatusEndpoint extends EndpointAbstract
{
    /**
     * @var string
     */
    protected $resourcePath = 'status';

    protected function getResourceObject(): BaseResource
    {
        return new Status($this->client);
    }

    /**
     * Retrieve a status for the API.
     *
     * @throws ApiException
     */
    public function get(): BaseResource
    {
        $result = $this->client->performHttpCall(
            self::REST_READ,
            $this->getResourcePath().$this->buildQueryString([]),
            null,
            true
        );

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }
}
