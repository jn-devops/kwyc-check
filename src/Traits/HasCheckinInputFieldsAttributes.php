<?php

namespace Homeful\KwYCCheck\Traits;

use Homeful\KwYCCheck\Models\Lead;
use Illuminate\Support\Arr;

trait HasCheckinInputFieldsAttributes
{
    const EMAIL_NDX = 'body.inputs.email';
    const MOBILE_NDX = 'body.inputs.mobile';
    const CODE_NDX = 'body.inputs.code';
    const IDENTIFIER_NDX = 'body.inputs.identifier';
    const CHOICE_NDX = 'body.inputs.choice';

    public function getEmailAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::EMAIL_NDX);
    }

    public function getMobileAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::MOBILE_NDX);
    }

    public function getCodeAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::CODE_NDX);
    }

    public function getIdentifierAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::IDENTIFIER_NDX);
    }

    public function getChoiceAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::CHOICE_NDX);
    }
}
