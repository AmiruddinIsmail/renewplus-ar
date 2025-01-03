<?php

namespace App\Http\Controllers\Webhooks\Curlec;

use App\Features\Payments\Facades\PaymentManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcessCurlecInstantController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        PaymentManager::driver(config('services.payments.curlec.driver'))->processInstant($request->all());

        return response()->json(['message' => 'OK'], 200);
    }
}
