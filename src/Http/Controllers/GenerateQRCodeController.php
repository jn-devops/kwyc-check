<?php

namespace Homeful\KwYCCheck\Http\Controllers;

use Homeful\KwYCCheck\Actions\GenerateQRCodeAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenerateQRCodeController extends Controller
{
    public function __construct(protected GenerateQRCodeAction $action){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $response = with($this->action->run($this->getInputsWithDefaultValues($request)), function (string $qr_code) {
            return [ 'qr_code' => $qr_code ];
        });

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getInputsWithDefaultValues(Request $request): array
    {
        return $request->all();
    }
}
