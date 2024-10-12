<?php

namespace Homeful\KwYCCheck\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Data;
use Illuminate\Support\Arr;

class DataData extends Data
{
    public function __construct(
        public string $status,
        public int $statusCode,
        public string $idType,
        public string $idImageUrl,
        public string $selfieImageUrl,
        public FieldsExtractedData $fieldsExtracted,
        public IdChecksData $idChecks,
        public SelfieChecksData $selfieChecks,
        public string $by,
    ){}

    public static function fromObject(array $object): self
    {
        return new self (
            status: Arr::get($object, 'status'),
            statusCode: Arr::get($object, 'statusCode'),
            idType: Arr::get($object, 'idType'),
            idImageUrl: Arr::get($object, 'idImageUrl'),
            selfieImageUrl: Arr::get($object, 'selfieImageUrl'),
            fieldsExtracted: FieldsExtractedData::from(Arr::get($object, 'fieldsExtracted')),
            idChecks: IdChecksData::from(Arr::get($object, 'idChecks')),
            selfieChecks: SelfieChecksData::from(Arr::get($object, 'selfieChecks')),
            by: Arr::get($object, 'by'),
        );
    }
}

class FieldsExtractedData extends Data
{
    public function __construct(
        public ?string $type,
        public ?string $idNumber,
        public ?string $dateOfIssue,
        public ?string $dateOfExpiry,
        public ?string $countryCode,
        public ?string $mrzString,
        public ?string $firstName,
        public ?string $middleName,
        public ?string $lastName,
        public ?string $fullName,
        public ?string $dateOfBirth,
        public ?string $address,
        public ?string $gender,
        public ?string $placeOfBirth,
        public ?string $placeOfIssue,
        public ?string $yearOfBirth,
        public ?string $age,
        public ?string $fatherName,
        public ?string $motherName,
        public ?string $husbandName,
        public ?string $spouseName,
        public ?string $nationality,
        public ?string $homeTown,
    ){}
}

class IdChecksData extends Data
{
    public function __construct(
        public string|Optional $blur,
        public string|Optional $glare,
        public string|Optional $black_and_white,
        public string|Optional $captured_from_screen,
        public string|Optional $partial_id
    ){}

    public static function prepareForPipeline(Collection|array $properties) : array
    {
        $props = [];
        foreach ($properties as $key => $value) {
            $key = strtolower( str_replace( " ", "_", $key ) );
            $props[$key] = $value;
        };

        return $props;
    }
}

class SelfieChecksData extends Data
{
    public function __construct(
        public string|Optional $blur,
        public string|Optional $eyes_closed,
        public string|Optional $mask_present,
        public string|Optional $multiple_faces
    )
    {
    }

    public static function prepareForPipeline(Collection|array $properties): array
    {
        $props = [];
        foreach ($properties as $key => $value) {
            $key = strtolower(str_replace(" ", "_", $key));
            $props[$key] = $value;
        };

        return $props;
    }
}
