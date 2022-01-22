<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RingBellRequest;
use App\Models\Bell;
use Illuminate\Http\Response;

class RingBellController extends Controller
{
    public function __invoke(RingBellRequest $request, Bell $bell): Response
    {
        if (! $bell->active) {
            abort(409);
        }

        $bell->ring($request->input('volume'), $request->input('events'));

        return response()->noContent();
    }
}
