<?php

namespace Homeful\KwYCCheck\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Homeful\KwYCCheck\KwYCCheck
 *
 * @method string generateCampaignQRCOde(array $query_params = null, string $url = null)
 */
class KYC extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Homeful\KwYCCheck\KwYCCheck::class;
    }
}
