<?php

namespace Homeful\KwYCCheck\Http\Controllers;

use Homeful\KwYCCheck\Actions\AttachLeadMediaAction;
use Illuminate\Support\Facades\Validator;
use Homeful\KwYCCheck\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttachLeadMediaController extends Controller
{
    /**
     * @param Lead $lead
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Lead $lead, Request $request): JsonResponse
    {
        $action = app(AttachLeadMediaAction::class);
        $validated = Validator::validate($request->all(), $action->rules());
        $lead = $action->run($lead, $validated);
        $response = $lead instanceof Lead
            ? ['lead' => $lead->id, 'uploads' => $lead->uploads]
            : [];

        return response()->json($response);
    }
}
