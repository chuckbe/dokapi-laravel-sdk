<?php

declare(strict_types=1);

namespace Dokapi\Api\Endpoints;

use Dokapi\Api\Exceptions\ApiException;
use Dokapi\Api\Resources\BaseCollection;
use Dokapi\Api\Resources\BaseResource;
use Dokapi\Api\Resources\LazyCollection;
use Dokapi\Api\Resources\ParticipantRegistration;
use Dokapi\Api\Resources\ParticipantRegistrationCollection;
use Dokapi\Api\Resources\ResourceFactory;
use Dokapi\Api\Resources\Status;

final class ParticipantRegistrationEndpoint extends CollectionEndpointAbstract
{
    /**
     * @var string
     */
    protected $resourcePath = 'participant-registrations';

    protected function getResourceObject(): BaseResource
    {
        return new ParticipantRegistration($this->client);
    }

    /**
     * Get the collection object that is used by this API endpoint. Every API endpoint uses one type of collection object.
     */
    protected function getResourceCollectionObject(int $count, ?string $lastEvaluatedKey = null): ParticipantRegistrationCollection
    {
        return new ParticipantRegistrationCollection($this->client, $count, $lastEvaluatedKey);
    }

    /**
     * Returns a paginated list for participant registrations for a client.
     *
     * @param ?string      $from    the first resource ID you want to include in your list
     * @param array<mixed> $filters
     *
     * @throws ApiException
     */
    public function page(
        ?string $from = null,
        ?int $limit = null,
        array $filters = [],
    ): BaseCollection {
        return $this->restList($from, $limit, $filters);
    }

    /**
     * Returns all participant registrations for a client.
     *
     * @param ?string      $from    the first resource ID you want to include in your list
     * @param array<mixed> $filters
     *
     * @throws ApiException
     *
     * @return LazyCollection<int, BaseResource>
     */
    public function all(
        ?string $from = null,
        ?int $limit = null,
        array $filters = [],
    ): LazyCollection {
        return $this->restIterator($from, $limit, $filters);
    }

    /**
     * This call retrieves the Peppol participant from the Dokapi database via the participant identifier. This does not perform a lookup in the SMP!
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

        $apiMethod = $this->getResourcePath().'/find'.$this->buildQueryString($queryString);

        $result = $this->client->performHttpCall(
            self::REST_READ,
            $apiMethod
        );

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }

    /**
     * Register a participant within SMP via the Dokapi SMP client.
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
            $this->parseRequestBody($body)
        );

        if (isset($result->participantRegistration) && is_object($result->participantRegistration)) {
            foreach (get_object_vars($result->participantRegistration) as $key => $value) {
                $result->{$key} = $value;
            }

            unset($result->participantRegistration);
        }

        return ResourceFactory::createFromApiResult($result, $this->getResourceObject());
    }

    /**
     * Deregister a participant within SMP via the Dokapi SMP client.
     *
     * @param array<mixed> $data
     *
     * @throws ApiException
     */
    public function delete(array $data = []): BaseResource
    {
        $result = $this->client->performHttpCall(
            self::REST_DELETE,
            $this->getResourcePath(),
            $this->parseRequestBody($data),
            true
        );

        return ResourceFactory::createFromApiResult($result, new Status($this->client));
    }

    /**
     * This call creates/updates the business card information associated with a specified Participant Identifier.
     *
     * @param array<mixed> $data
     *
     * @throws ApiException
     */
    public function businessCard(array $data = []): BaseResource
    {
        $result = $this->client->performHttpCall(
            self::REST_PUT,
            $this->getResourcePath().'/business-cards',
            $this->parseRequestBody($data),
            true
        );

        return ResourceFactory::createFromApiResult($result, new Status($this->client));
    }

    /**
     * This call registers documents and related process identifiers for a Peppol identifier within SMP via the Dokapi SMP client.
     *
     * @param array<mixed> $data
     *
     * @throws ApiException
     */
    public function registerDocumentType(array $data = []): BaseResource
    {
        $result = $this->client->performHttpCall(
            self::REST_CREATE,
            $this->getResourcePath().'/documents',
            $this->parseRequestBody($data),
            true
        );

        return ResourceFactory::createFromApiResult($result, new Status($this->client));
    }

    /**
     * This call registers documents and related process identifiers for a Peppol identifier within SMP via the Dokapi SMP client.
     *
     * @param array<mixed> $data
     *
     * @throws ApiException
     */
    public function deregisterDocumentType(array $data = []): BaseResource
    {
        $result = $this->client->performHttpCall(
            self::REST_DELETE,
            $this->getResourcePath().'/documents',
            $this->parseRequestBody($data),
            true
        );

        return ResourceFactory::createFromApiResult($result, new Status($this->client));
    }
}
