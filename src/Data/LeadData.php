<?php

namespace Homeful\KwYCCheck\Data;

use Homeful\Contacts\Data\ContactData;
use Homeful\KwYCCheck\Models\Lead;
use Spatie\LaravelData\Data;

class LeadData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $address,
        public string $birthdate,
        public ?string $email,
        public ?string $mobile,
        public ?string $code,
        public ?string $identifier,
        public ?string $choice,
        public string $id_type,
        public string $id_number,
        public ?string $id_image_url,
        public ?string $selfie_image_url,
        public ?string $campaign_document_url,
        public ?ContactData $contact,
    ) {}

    public static function fromModel(Lead $lead): self
    {
        return new self (
            id: $lead->id,
            name: $lead->name,
            address: $lead->address,
            birthdate: $lead->birthdate,
            email: $lead->email,
            mobile: $lead->mobile,
            code: $lead->code,
            identifier: $lead->identifier,
            choice: $lead->choice,
            id_type: $lead->id_type,
            id_number: $lead->id_number,
            id_image_url: $lead->id_image_url,
            selfie_image_url: $lead->selfie_image_url,
            campaign_document_url: $lead->campaign_document_url,
            contact: null == $lead->contact ? null : ContactData::fromModel($lead->contact)
        );
    }
}
