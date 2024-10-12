<?php

namespace Homeful\KwYCCheck\Data;

use Spatie\LaravelData\Data;
use Illuminate\Support\Arr;

class InputsData extends Data
{
    public function __construct(
        public ?string $email,
        public ?string $mobile,
        public ?string $code,
        public ?string $identifier,
        public ?string $choice,
        public ?string $location,
    ){}
}
