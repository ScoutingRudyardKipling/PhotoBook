<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Clearance extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'clearance';
    }
}
