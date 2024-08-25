<?php

namespace Homeful\KwYCCheck\Actions;

use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Homeful\KwYCCheck\KwYCCheck;

class GenerateQRCodeAction
{
    use AsAction;

    /**
     * @param array $validated_inputs_with_defaults
     * @return string
     * @throws \Exception
     */
    protected function generate(array $validated_inputs_with_defaults): string
    {
        return (new KwYCCheck)->generateCampaignQRCOde(query_params: $validated_inputs_with_defaults);
    }

    /**
     * @param array $inputs_with_default_values
     * @return string
     * @throws \Exception
     */
    public function handle(array $inputs_with_default_values): string
    {
        $validated_inputs_with_default_values = Validator::validate($inputs_with_default_values, $this->rules());

        return $this->generate($validated_inputs_with_default_values);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'choice' => ['nullable', 'string'],
        ];
    }
}
