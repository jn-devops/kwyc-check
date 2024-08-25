<?php

namespace Homeful\KwYCCheck\Actions;


use Lorisleiva\Actions\Concerns\AsAction;
use Homeful\KwYCCheck\Models\Lead;
use Illuminate\Support\Arr;

/**
 * Class AttachLeadMediaAction
 *
 */
class AttachLeadMediaAction
{
    use AsAction;

    public function handle(Lead $lead, array $attribs): Lead
    {
        $lead->update($attribs);
        $lead->save();

        return $lead;
    }

    public function rules(): array
    {
        return Arr::mapWithKeys(app(Lead::class)->getMediaFieldNames(), function (string $mediaFieldName) {
            return [
                $mediaFieldName => ['nullable', 'url'],
            ];
        });
    }
}
