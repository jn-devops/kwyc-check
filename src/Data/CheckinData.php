<?php

namespace Homeful\KwYCCheck\Data;

use Spatie\LaravelData\Data;
use Illuminate\Support\Arr;

class CheckinData extends Data
{
    public function __construct(
        public string $code,
        public CampaignData $campaign,
        public InputsData $inputs,
        public DataData $data,
    ){}

    public static function fromObject(array $object): self
    {
        return new self (
            code: Arr::get($object, 'body.code'),
            campaign: CampaignData::fromObject(Arr::get($object, 'body.campaign')),
            inputs: InputsData::from(Arr::get($object, 'body.inputs')),
            data: DataData::from(Arr::get($object, 'body.data')),
        );
    }
}
