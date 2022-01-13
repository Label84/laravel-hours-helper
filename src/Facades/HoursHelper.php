<?php

namespace Label84\HoursHelper\Facades;

use Illuminate\Support\Facades\Facade;
use Label84\HoursHelper\HoursHelper as HoursHelperFacade;

class HoursHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HoursHelperFacade::class;
    }
}
