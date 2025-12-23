<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class ServiceMetadataCollection extends BaseCollection
{
    public function getCollectionResourceName(): string
    {
        return 'serviceInformation';
    }
}
