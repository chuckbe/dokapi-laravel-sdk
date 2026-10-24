<?php

declare(strict_types=1);

namespace Dokapi\Tests\Unit;

use Dokapi\Api\DokapiApiClient;
use Dokapi\Api\Resources\BusinessCard;
use Dokapi\Api\Resources\Lookup;
use Dokapi\Api\Resources\ParticipantIdentifier;
use Dokapi\Api\Resources\ResourceFactory;
use Dokapi\Api\Resources\ServiceGroup;
use Dokapi\Api\Resources\ServiceMetadataCollection;
use Dokapi\Tests\TestCase;

final class ResourceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_builds_a_lookup_with_a_business_card(): void
    {
        /** @var Lookup $lookup */
        $lookup = ResourceFactory::createFromApiResult($this->lookupResult(), $this->lookup());

        $this->assertInstanceOf(BusinessCard::class, $lookup->businessCard);
        $this->assertInstanceOf(ParticipantIdentifier::class, $lookup->businessCard->participantIdentifier);
        $this->assertSame('9925:be0123456789', $lookup->businessCard->participantIdentifier->value);
    }

    /**
     * @test
     */
    public function it_leaves_an_empty_business_card_null(): void
    {
        $result = $this->lookupResult();
        $result->businessCard = null;

        /** @var Lookup $lookup */
        $lookup = ResourceFactory::createFromApiResult($result, $this->lookup());

        $this->assertNull($lookup->businessCard);
        $this->assertInstanceOf(ServiceGroup::class, $lookup->serviceGroup);
        $this->assertSame('9925:be0123456789', $lookup->serviceGroup->participantIdentifier->value);
        $this->assertInstanceOf(ServiceMetadataCollection::class, $lookup->serviceMetadata);
        $this->assertCount(1, $lookup->serviceMetadata);
    }

    /**
     * @test
     */
    public function it_leaves_an_empty_nested_list_null(): void
    {
        $result = $this->lookupResult();
        $result->serviceMetadata = null;

        /** @var Lookup $lookup */
        $lookup = ResourceFactory::createFromApiResult($result, $this->lookup());

        $this->assertNull($lookup->serviceMetadata);
    }

    private function lookup(): Lookup
    {
        return new Lookup($this->app->make(DokapiApiClient::class));
    }

    private function lookupResult(): object
    {
        $participantIdentifier = ['scheme' => 'iso6523-actorid-upis', 'value' => '9925:be0123456789'];

        $result = [
            'serviceGroup' => [
                'participantIdentifier' => $participantIdentifier,
                'serviceMetadataReferenceCollection' => ['serviceMetadataReference' => []],
                'extension' => null,
            ],
            'serviceMetadata' => [
                [
                    'serviceInformation' => [
                        'participantIdentifier' => $participantIdentifier,
                        'documentIdentifier' => ['scheme' => 'busdox-docid-qns', 'value' => 'invoice'],
                        'processList' => ['process' => []],
                        'extension' => null,
                    ],
                ],
            ],
            'businessCard' => [
                'participantIdentifier' => $participantIdentifier,
                'businessEntity' => [],
            ],
        ];

        return json_decode((string) json_encode($result));
    }
}
