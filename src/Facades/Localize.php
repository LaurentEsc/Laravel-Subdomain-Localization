<?php namespace LaurentEsc\Localization\Facades;

use Illuminate\Support\Facades\Facade;

class Localize extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'localization.localize';
    }

}