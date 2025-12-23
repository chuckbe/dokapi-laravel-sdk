<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class DocumentIdentifier extends BaseResource
{
    /**
     * The optional scheme for this documentTypeIdentifier.
     * If none is given, the default scheme value will be used (busdox-docid-qns).
     *
     * @var string
     */
    public $scheme;

    /**
     * The mandatory value for this documentTypeIdentifier.
     * Example for an identifier that follow the busdox-docid-qns scheme: urn:oasis:names:specification:ubl:schema:xsd:Invoice-2::Invoice##urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0::2.1.
     *
     * @var string
     */
    public $value;
}
