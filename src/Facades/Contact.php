<?php

namespace BinomeWay\NovaContactTool\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BinomeWay\NovaContactTool\Contact
 */
class Contact extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BinomeWay\NovaContactTool\Services\Contact::class;
    }
}
