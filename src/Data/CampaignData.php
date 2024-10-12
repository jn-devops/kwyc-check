<?php

namespace Homeful\KwYCCheck\Data;

use Spatie\LaravelData\Data;
use Illuminate\Support\Arr;

class CampaignData extends Data
{
    public function __construct(
        public string $code,
        public string $name,
        public OrganizationData $organization,
        public AgentData $agent,
    ){}

    public static function fromObject(array $object): self
    {
        return new self (
            code: Arr::get($object, 'code'),
            name: Arr::get($object, 'name'),
            organization: OrganizationData::fromObject(Arr::get($object, 'organization')),
            agent: AgentData::fromObject(Arr::get($object, 'agent')),
        );
    }
}

class OrganizationData extends Data
{
    public function __construct(
        public string $name,
        public ?string $domain,
        public bool $personal,
        public bool $verified,
    ){}

    public static function fromObject(array $object): self
    {
        return new self (
            name: Arr::get($object, 'name'),
            domain: Arr::get($object, 'domain'),
            personal: Arr::get($object, 'personal'),
            verified: Arr::get($object, 'verified'),
        );
    }
}

class AgentData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $mobile,
    ){}

    public static function fromObject(array $object): self
    {
        return new self (
            name: Arr::get($object, 'name'),
            email: Arr::get($object, 'email'),
            mobile: Arr::get($object, 'mobile'),
        );
    }
}
