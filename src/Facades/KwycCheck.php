<?php

namespace Homeful\KwycCheck\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\KwycCheck\KwycCheck
 */
class KwycCheck extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\KwycCheck\KwycCheck::class;
    }
}
