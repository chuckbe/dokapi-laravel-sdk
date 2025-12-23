<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class BusinessEntity extends BaseResource
{
    /**
     * The multilingual name of the business entity.
     *
     * @var NameCollection
     */
    public $name;

    /**
     * The mandatory ISO 3166-2 country code (e.g. "AT" for Austria or "NO" for Norway).
     *
     * @var string
     */
    public $countryCode;

    /**
     * This element may contain specific geographic information. It may be an address or simply a region.
     *
     * @var string
     */
    public $geographicalInformation;

    /**
     * This element is meant to contain additional identifiers for this entity like VAT number, national organisation number etc.
     *
     * @var IdentifierCollection
     */
    public $identifier;

    /**
     * This element may contain URIs linking to the business entity websites.
     *
     * @var array<int, string>
     */
    public $websiteURI;

    /**
     * This element is meant to contain business contact points to get in touch with the respective business entity.
     *
     * @var ContactCollection
     */
    public $contact;

    /**
     * This element contains all other information that might be relevant but is not necessarily be contained in a structured field.
     *
     * @var string
     */
    public $additionalInformation;

    /**
     * The optional date when the participant was registered for Peppol.
     *
     * @var string
     */
    public $registrationDate;

    /**
     * @param array<int, object> $name
     */
    public function name(array $name): BaseCollection
    {
        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            Name::class,
            $name
        );
    }

    /**
     * @param array<int, object> $identifierCollection
     */
    public function identifier(array $identifierCollection): BaseCollection
    {
        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            Identifier::class,
            $identifierCollection
        );
    }

    /**
     * @param array<int, object> $contact
     */
    public function contact(array $contact): BaseCollection
    {
        return ResourceFactory::createBaseResourceCollection(
            $this->client,
            Contact::class,
            $contact
        );
    }
}
