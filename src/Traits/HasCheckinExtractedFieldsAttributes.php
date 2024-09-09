<?php

namespace Homeful\KwYCCheck\Traits;

use Homeful\KwYCCheck\Models\Lead;
use Illuminate\Support\Arr;

trait HasCheckinExtractedFieldsAttributes
{
    const NAME_NDX = 'body.data.fieldsExtracted.fullName';
    const ADDRESS_NDX = 'body.data.fieldsExtracted.address';
    const BIRTHDATE_NDX = 'body.data.fieldsExtracted.dateOfBirth';
    const ID_TYPE_NDX = 'body.data.idType';
    const ID_NUMBER_NDX = 'body.data.fieldsExtracted.idNumber';
    const ID_IMAGE_URL_NDX = 'body.data.idImageUrl';
    const SELFIE_IMAGE_URL_NDX = 'body.data.selfieImageUrl';
    const CAMPAIGN_DOCUMENT_URL_NDX = 'body.campaignDocumentUrl';

    public function getNameAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::NAME_NDX);
    }

    public function getAddressAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::ADDRESS_NDX);
    }

    public function getBirthdateAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::BIRTHDATE_NDX);
    }

    public function getIdTypeAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::ID_TYPE_NDX);
    }

    public function getIdNumberAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::ID_NUMBER_NDX);
    }

    public function getIdImageUrlAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::ID_IMAGE_URL_NDX);
    }

    public function getSelfieImageUrlAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::SELFIE_IMAGE_URL_NDX);
    }

    public function getCampaignDocumentUrlAttribute(): ?string
    {
        return Arr::get($this->checkin, Lead::CAMPAIGN_DOCUMENT_URL_NDX);
    }
}
