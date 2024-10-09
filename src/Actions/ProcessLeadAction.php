<?php

namespace Homeful\KwYCCheck\Actions;

use Homeful\KwYCCheck\Events\LeadProcessed;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Homeful\KwYCCheck\Models\Lead;

class ProcessLeadAction
{
    use AsAction;

    /**
     * @param array $checkin_payload - result payload from Hyperverge
     * @return Lead|null
     */
    public function handle(array $checkin_payload): ?Lead
    {
        $validated_filtered_attributes = Validator::validate($checkin_payload, $this->rules());
        $lead = $this->createLead($validated_filtered_attributes, $checkin_payload);
        if ($lead instanceof  Lead) {
            LeadProcessed::dispatch($lead);

            return $lead;
        }

        return null;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'array'],
            'body.campaign.agent' => ['required', 'array'],
            'body.campaign.agent.name' => ['required', 'string'],
            'body.campaign.agent.email' => ['required', 'email'],
            'body.campaign.agent.mobile' => ['required', 'string'],
            'body.inputs' => ['required', 'array'],
            'body.inputs.email' => ['nullable', 'email'],
            'body.inputs.mobile' => ['nullable', 'string', 'min:11'],
            'body.inputs.code' => ['nullable', 'string'],
            'body.inputs.identifier' => ['nullable', 'string'],
            'body.inputs.choice' => ['nullable', 'string'],
            'body.inputs.location' => ['nullable', 'string'],
            'body.data' => ['required', 'array'],
            'body.data.idType' => ['required', 'string'],
            'body.data.fieldsExtracted' => ['required', 'array'],
            'body.data.fieldsExtracted.fullName' => ['required', 'string'],
            'body.data.fieldsExtracted.address' => ['required', 'string'],
            'body.data.fieldsExtracted.dateOfBirth' => ['required', 'date'],
            'body.data.fieldsExtracted.idNumber' => ['required', 'string'],
            'body.data.idImageUrl' => ['required', 'string'],
            'body.data.selfieImageUrl' => ['required', 'string'],
        ];
    }

    /**
     * @param array $validated_filtered_attributes
     * @param array $checkin_payload
     * @return Lead|null
     */
    protected function createLead(array $validated_filtered_attributes, array $checkin_payload): ?Lead
    {
        return app(Lead::class)->create([
            'checkin' => $checkin_payload
        ]);
    }
}
