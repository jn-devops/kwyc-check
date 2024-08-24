<?php

namespace Homeful\KwYCCheck\Actions;

use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Homeful\KwYCCheck\Models\Lead;

class ProcessLeadAction
{
    use AsAction;

    /**
     * @param array $checkin_payload - result payload from Hyperverge
     * @return Lead
     */
    public function handle(array $checkin_payload): Lead
    {
        $validated_filtered_attributes = Validator::validate($checkin_payload, $this->rules());

        return $this->createLead($validated_filtered_attributes, $checkin_payload);
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
            'body.inputs.email' => ['required', 'email'],
            'body.inputs.mobile' => ['required', 'string', 'min:11'],
            'body.inputs.code' => ['required', 'string'],
            'body.inputs.identifier' => ['required', 'string'],
            'body.inputs.choice' => ['required', 'string'],
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
     * @return Lead
     */
    protected function createLead(array $validated_filtered_attributes, array $checkin_payload): Lead
    {
        return app(Lead::class)->create([
            'checkin' => $checkin_payload
        ]);
    }
}
