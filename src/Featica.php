<?php

namespace Featica;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Featica\FeaticaManager
 */
class Featica extends Facade
{
    // Types
    const TYPE_USER = 'user';
    const TYPE_TEAM = 'team';
    const TYPE_SYSTEM = 'system'; // TODO: choose either 'system' or 'global'
    const TYPE_GLOBAL = 'global'; // TODO: choose either 'system' or 'global'

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FeaticaManager::class;
    }
}
