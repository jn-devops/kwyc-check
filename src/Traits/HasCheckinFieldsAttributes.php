<?php

namespace Homeful\KwYCCheck\Traits;

use Homeful\KwYCCheck\Models\Lead;
use Illuminate\Support\Arr;

trait HasCheckinFieldsAttributes
{
    const CHECKIN_CODE_NDX = 'body.code';

    public function getCheckinCodeAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::CHECKIN_CODE_NDX);
    }
}
