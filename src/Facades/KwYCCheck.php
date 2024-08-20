<?php

namespace Homeful\KwYCCheck\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\KwYCCheck\KwYCCheck
 */
class KwYCCheck extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\KwycCheck\KwYCCheck::class;
    }
}
