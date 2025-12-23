<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

final class Name extends BaseResource
{
    /**
     * The value of this name.
     *
     * @var string
     */
    public $value;

    /**
     * The optional language of this name.
     *
     * @var string
     */
    public $language;
}
