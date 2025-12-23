<?php

declare(strict_types=1);

namespace Dokapi\Api\Endpoints;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\Resources\BaseResource;
use Dokapi\Api\Resources\Lookup;
use Dokapi\Api\Resources\ResourceFactory;

final class LookupEndpoint extends EndpointAbstract
{
    /**
     * @var string
     */
    protected $resourcePath = 'participant-registrations';

    protected function getResourceObject(): BaseResource
    {
        return new Lookup($this->client);
    }

    /**
     * Perform a lookup for a participant in the SMK.
     *
     * @throws ApiException
     */
    public function find(string $value, ?string $scheme = null): BaseResource
    {
        $queryString = [];

        if (null !== $scheme) {
            $queryString['scheme'] = $scheme;
        }

        $queryString['value'] = $value;

        $apiMethod = $this->getResourcePath().'/lookup'.$this->buildQueryString($queryString);

        $result = $this->client->performHttpCall(
            self::REST_READ,
            $apiMethod,
            null,
            false
        );

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }
}
