<?php

namespace Homeful\KwYCCheck\Http\Controllers;

use Homeful\KwYCCheck\Actions\CreateLeadContactAction;
use Homeful\KwYCCheck\Data\LeadData;
use Homeful\KwYCCheck\Models\Lead;
use Illuminate\Http\Request;

class CreateLeadContactController extends Controller
{
    /**
     * @param Lead $lead
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Lead $lead, Request $request): \Illuminate\Http\RedirectResponse
    {
        $lead = app(CreateLeadContactAction::class)->run($lead, $request->all());

        return back()->with('event', [
            'name' => 'lead-contact.created',
            'data' => LeadData::from($lead),
        ]);
    }
}
