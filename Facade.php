<?php

namespace Apollo16\Core\Payload;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * Laravel Facade.
 *
 * @author      mohammad.anang  <m.anangnur@gmail.com>
 */

class Facade extends LaravelFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'apollo16.payload';
    }
}