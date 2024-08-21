<?php

namespace Homeful\KwYCCheck\Http\Controllers;

use Homeful\KwYCCheck\Actions\ProcessLeadAction;
use Homeful\KwYCCheck\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessLeadController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $lead = app(ProcessLeadAction::class)->run($request->all());
        $response = $lead instanceof Lead
            ? ['code' => $lead->code, 'status' => $lead->exists]
            : [];

        return response()->json($response);
    }
}
