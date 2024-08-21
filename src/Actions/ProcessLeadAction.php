<?php

namespace Homeful\KwYCCheck\Actions;

use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Homeful\KwYCCheck\Models\Lead;
use Illuminate\Support\Arr;

class ProcessLeadAction
{
    use AsAction;

    /**
     * @param array $captured_attribs - result payload from Hyperverge
     * @return Lead
     */
    public function handle(array $captured_attribs): Lead
    {
        $validated = Validator::validate($captured_attribs, $this->rules());

        return $this->createLead($validated);
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
            'body.inputs.code' => ['required', 'string'],
            'body.inputs.email' => ['required', 'email'],
            'body.inputs.mobile' => ['required', 'string', 'min:11'],
            'body.data' => ['required', 'array'],
            'body.data.idType' => ['required', 'string'],
            'body.data.fieldsExtracted' => ['required', 'array'],
            'body.data.fieldsExtracted.fullName' => ['required', 'string'],
            'body.data.fieldsExtracted.dateOfBirth' => ['required', 'date'],
            'body.data.fieldsExtracted.address' => ['required', 'string'],
            'body.data.fieldsExtracted.idNumber' => ['required', 'string'],
            'body.data.idImageUrl' => ['required', 'string'],
            'body.data.selfieImageUrl' => ['required', 'string'],
        ];
    }

    /**
     * @param array $validated - filtered captured attribs
     * @return Lead
     */
    protected function createLead(array $validated): Lead
    {
        $fieldsExtracted = Arr::get($validated, 'body.data.fieldsExtracted');
        $email = Arr::get($validated, 'body.inputs.email');
        $mobile = Arr::get($validated, 'body.inputs.mobile');
        $code = Arr::get($validated, 'body.inputs.code');
        $idType = Arr::get($validated, 'body.data.idType');
        $idImageUrl = Arr::get($validated, 'body.data.idImageUrl');
        $selfieImageUrl = Arr::get($validated, 'body.data.selfieImageUrl');

        return app(Lead::class)->create([
            'name' => Arr::get($fieldsExtracted, 'fullName'),
            'address' => Arr::get($fieldsExtracted, 'address'),
            'birthdate' => Arr::get($fieldsExtracted, 'dateOfBirth'),
            'email' => $email,
            'mobile' => $mobile,
            'code' => $code,
            'id_type' => $idType,
            'id_number' => Arr::get($fieldsExtracted, 'idNumber'),
            'id_image_url' => $idImageUrl,
            'selfie_image_url' => $selfieImageUrl,
        ]);
    }
}
