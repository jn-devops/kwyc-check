<?php

namespace Homeful\KwYCCheck\Actions;

use Homeful\Contacts\Actions\PersistContactAction;
use Homeful\KwYCCheck\Events\LeadContactCreated;
use Lorisleiva\Actions\Concerns\AsAction;
use Homeful\KwYCCheck\Models\Lead;

class CreateLeadContactAction
{
    use AsAction;

    /**
     * @param Lead $lead
     * @param array $attribs
     * @return Lead
     */
    public function handle(Lead $lead, array $attribs): Lead
    {
        $contact = PersistContactAction::run($attribs);
        $lead->setAttribute('contact', $contact);
        $lead->save();
        LeadContactCreated::dispatch($lead);

        return $lead;
    }
}
