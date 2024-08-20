<?php

namespace Homeful\KwycCheck\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\KwycCheck\KwYCCheck
 */
class KwycCheck extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\KwycCheck\KwYCCheck::class;
    }
}
