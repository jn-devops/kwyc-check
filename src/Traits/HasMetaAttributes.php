<?php

namespace Homeful\KwYCCheck\Traits;

use Homeful\KwYCCheck\Models\Lead;

trait HasMetaAttributes
{
    public function initializeHasMetaAttributes(): void
    {
        $this->mergeFillable(['email', 'mobile', 'code']);
    }

    public function setEmailAttribute(string $value): self
    {
        $this->getAttribute('meta')->set(Lead::EMAIL_FIELD, $value);

        return $this;
    }

    public function getEmailAttribute(): ?string
    {
        $default = null;

        return $this->getAttribute('meta')->get(Lead::EMAIL_FIELD) ?? $default;
    }

    public function setMobileAttribute(string $value): self
    {
        $this->getAttribute('meta')->set(Lead::MOBILE_FIELD, $value);

        return $this;
    }

    public function getMobileAttribute(): ?string
    {
        $default = null;

        return $this->getAttribute('meta')->get(Lead::MOBILE_FIELD) ?? $default;
    }

    public function setCodeAttribute(string $value): self
    {
        $this->getAttribute('meta')->set(Lead::CODE_FIELD, $value);

        return $this;
    }

    public function getCodeAttribute(): ?string
    {
        $default = null;

        return $this->getAttribute('meta')->get(Lead::CODE_FIELD) ?? $default;
    }
}
