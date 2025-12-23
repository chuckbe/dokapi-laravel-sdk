<?php

declare(strict_types=1);

namespace Dokapi\Facades;

use Illuminate\Support\Facades\Facade;
use Dokapi\Api\DokapiApiClient;
use Dokapi\DokapiManager;

/**
 * (Facade) Class Dokapi.
 *
 * @method static DokapiApiClient api()
 */
final class Dokapi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DokapiManager::class;
    }
}
