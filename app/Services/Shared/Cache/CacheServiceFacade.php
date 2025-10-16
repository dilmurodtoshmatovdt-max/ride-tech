<?php

namespace App\Services\Shared\Cache;

use Illuminate\Support\Facades\Facade;

/*
* @see App\Services\Cache
*/

class CacheServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cacheService';
    }
}
