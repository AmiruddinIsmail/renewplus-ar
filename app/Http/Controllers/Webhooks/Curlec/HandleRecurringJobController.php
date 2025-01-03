<?php

namespace App\Http\Controllers\Webhooks\Curlec;

use App\Features\Payments\Webhooks\HandleCollectionJob;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HandleRecurringJobController extends Controller
{
    public function __invoke(Request $request, HandleCollectionJob $handler)
    {
        $handler->handle($request->all());

        return response()
            ->json(['message' => 'OK'], 200);
    }
}
