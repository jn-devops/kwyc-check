<?php

namespace Homeful\KwYCCheck\Data;

use Homeful\Contacts\Data\ContactData;
use Homeful\Contacts\Models\Contact;
use Homeful\KwYCCheck\Models\Lead;
use Spatie\LaravelData\Data;

class LeadData extends Data
{
    public function __construct(
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
        public ?string $id_mark_url,
        public ?ContactData $contact,
    ) {}

    public static function fromModel(Lead $lead): self
    {
        return new self (
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
            id_mark_url: $lead->id_mark_url,
            contact: null == $lead->contact ? null : ContactData::fromModel($lead->contact)
        );
    }
}
