<?php

namespace Featica;

use Illuminate\Support\Facades\Facade;

class FeaticaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'featica';
    }
}
