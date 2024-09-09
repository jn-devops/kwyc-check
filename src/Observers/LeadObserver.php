<?php

namespace Homeful\KwYCCheck\Observers;

use Homeful\KwYCCheck\Actions\AttachLeadMediaAction;
use Homeful\KwYCCheck\Models\Lead;

class LeadObserver
{
    public function created(Lead $lead): void
    {
        $attribs = [
            'idImage' => $lead->id_image_url,
            'selfieImage' => $lead->selfie_image_url,
            'campaignDocument' => $lead->campaign_document_url,
        ];
        AttachLeadMediaAction::dispatch($lead, $attribs);
    }
}
