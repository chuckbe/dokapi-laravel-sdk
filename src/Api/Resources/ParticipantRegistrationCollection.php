<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class ParticipantRegistrationCollection extends CursorCollection
{
    public function getCollectionResourceName(): string
    {
        return 'Items';
    }

    protected function createResourceObject(): BaseResource
    {
        return new ParticipantRegistration($this->client);
    }
}
