<?php

namespace Wovosoft\AppBuilder;

use Illuminate\Support\Facades\Facade;

class AppBuilderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'appbuilder';
    }
}
