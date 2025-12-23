<?php

declare(strict_types=1);

namespace Dokapi\Api\Resources;

use ArrayObject;

abstract class BaseCollection extends ArrayObject
{
    /**
     * Total number of retrieved objects.
     *
     * @var int
     */
    public $count;

    /**
     * @var ?string
     */
    public $lastEvaluatedKey;

    public function __construct(int $count, ?string $lastEvaluatedKey = null)
    {
        $this->count = $count;
        $this->lastEvaluatedKey = $lastEvaluatedKey;

        parent::__construct();
    }

    /**
     * @return string|null
     */
    abstract public function getCollectionResourceName();
}
