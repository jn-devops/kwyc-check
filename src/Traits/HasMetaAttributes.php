<?php

namespace Homeful\KwYCCheck\Traits;

use Homeful\KwYCCheck\Models\Lead;

trait HasMetaAttributes
{
    const CHECKIN_FIELD = 'checkin';

    public function initializeHasMetaAttributes(): void
    {
        $this->mergeFillable(['checkin']);
    }

    public function setCheckinAttribute(array $value): self
    {
        $this->getAttribute('meta')->set(Lead::CHECKIN_FIELD, $value);

        return $this;
    }

    public function getCheckinAttribute(): ?array
    {
        $default = null;

        return $this->getAttribute('meta')->get(Lead::CHECKIN_FIELD) ?? $default;
    }
}
