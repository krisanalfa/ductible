<?php

namespace Zeek\Ductible\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Zeek\Ductible\Ductible
 */
class Ductible extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ductible';
    }
}
