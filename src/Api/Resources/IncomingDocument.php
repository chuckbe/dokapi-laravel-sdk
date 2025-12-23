<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class IncomingDocument extends BaseResource
{
    /**
     * The pre-signed URL of the incoming document / acknowledgement of download confirmation.
     *
     * @var string
     */
    public $message;
}
