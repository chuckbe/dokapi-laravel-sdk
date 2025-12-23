<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class Endpoint extends BaseResource
{
    /**
     * The endpoint reference.
     *
     * @var EndpointReferenceDto
     */
    public $endpointReferenceDto;

    /**
     * Set to true if the recipient requires business-level signatures for the message.
     *
     * @var bool
     */
    public $requireBusinessLevelSignature;

    /**
     * Indicates the minimum authentication level that recipient requires.
     * The specific semantics of this field is defined in a specific instance of the BUSDOX infrastructure.
     *
     * @var string
     */
    public $minimumAuthenticationLevel;

    /**
     * Activation date of the service.
     *
     * @var string
     */
    public $serviceActivationDate;

    /**
     * Expiration date of the service.
     *
     * @var string
     */
    public $serviceExpirationDate;

    /**
     * Holds the complete signing certificate of the recipient AP, as a PEM (base 64) encoded X509 DER formatted value.
     *
     * @var string
     */
    public $certificate;

    /**
     * A human readable description of the service.
     *
     * @var string
     */
    public $serviceDescription;

    /**
     * Represents a link to human readable contact information. This might also be an email address.
     *
     * @var string
     */
    public $technicalContactUrl;

    /**
     * A URL to human readable documentation of the service format. Usually a website.
     *
     * @var string
     */
    public $technicalInformationUrl;

    /**
     * Indicates the type of transport protocol that is being used between access points.
     * See Peppol Code Lists → Transport Profiles for a complete list.
     *
     * @var string
     */
    public $transportProfile;

    /**
     * The extension element may contain any XML element containing extra metadata about the endpoint type. Clients MAY ignore this element.
     *
     * @var string
     */
    public $extension;

    public function endpointReferenceDto(object $endpointReferenceDto): BaseResource
    {
        return ResourceFactory::createFromApiResult(
            $endpointReferenceDto,
            new EndpointReferenceDto($this->client)
        );
    }
}
