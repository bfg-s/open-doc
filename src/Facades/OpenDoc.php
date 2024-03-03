<?php

namespace Bfg\OpenDoc\Facades;

use Illuminate\Support\Facades\Facade;

class OpenDoc extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'open-doc';
    }
}
