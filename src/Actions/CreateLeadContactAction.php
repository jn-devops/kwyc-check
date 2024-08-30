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
        $lead->contact()->associate($contact);
        $lead->save();
        $lead->load('contact');
        LeadContactCreated::dispatch($lead);

        return $lead;
    }
}
