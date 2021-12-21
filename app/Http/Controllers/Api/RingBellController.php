<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bell;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RingBellController extends Controller
{
    public function __invoke(Request $request, Bell $bell): Response
    {
        $this->authorize('update', $bell);

        $bell->ring($request->input('volume'));

        return response()->noContent();
    }
}
