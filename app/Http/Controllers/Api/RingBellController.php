<?php

namespace App\Http\Controllers\Api;

use App\Models\Bell;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class RingBellController extends Controller
{
    public function __invoke(Bell $bell): Response
    {
        $this->authorize('update', $bell);

        $bell->ring();

        return response()->noContent();
    }
}
