<?php

namespace Map\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Map
 * @package Map\Laravel\Facades
 */
class Map extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'map';
    }
}