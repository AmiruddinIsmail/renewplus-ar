<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['throttle:payment-gateway'])
    ->prefix('/v1/webhooks')
    ->group(function (): void {
        Route::post('curlec/instant-payment', App\Http\Controllers\Webhooks\Curlec\ProcessCurlecInstantController::class)
            ->name('webhooks.curlec-instant-payment');

    });
